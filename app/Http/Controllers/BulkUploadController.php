<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHistory;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BulkUploadController extends Controller
{
    public function store(Request $request)
    {
        // Bulk imports can be large – remove PHP time limits and allocate ample memory
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $request->validate([
            'target_role' => 'required|in:admin,sta,stu', // admin=Coordinator, sta=Staff, stu=Student
        ]);

        $fullPath = null;
        $originalName = null;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $path = $file->storeAs('uploads/bulk', $fileName, 'public');
            $fullPath = storage_path('app/public/' . $path);
        } elseif ($request->filled('file_base64')) {
            $base64Data = $request->input('file_base64');
            $originalName = $request->input('file_name', 'bulk_upload.xlsx');
            if (str_contains($base64Data, ',')) {
                $base64Data = explode(',', $base64Data)[1];
            }
            $fileData = base64_decode($base64Data);
            if ($fileData !== false && strlen($fileData) > 0) {
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $path = 'uploads/bulk/' . $fileName;
                Storage::disk('public')->put($path, $fileData);
                $fullPath = storage_path('app/public/' . $path);
            }
        } elseif ($request->hasFile('file')) {
            // File was sent but has an upload error code (e.g. PHP temp folder permissions)
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            try {
                $path = $file->storeAs('uploads/bulk', $fileName, 'public');
                $fullPath = storage_path('app/public/' . $path);
            } catch (\Exception $e) {
                // Ignore
            }
        }

        if (!$fullPath || !file_exists($fullPath) || filesize($fullPath) === 0) {
            return back()->with('error', 'The file was not received or was empty. Please select a valid .csv, .xls, or .xlsx file.');
        }

        $ext = strtolower(pathinfo($originalName ?? 'upload.xlsx', PATHINFO_EXTENSION));
        if (!in_array($ext, ['csv', 'xlsx', 'xls', 'txt'])) {
            return back()->with('error', 'Invalid file format. Please upload an Excel (.xlsx, .xls) or CSV (.csv) file.');
        }

        // Count total rows super fast
        $totalRows = 0;
        try {
            if (in_array($ext, ['csv', 'txt'])) {
                $lineCount = 0;
                $handle = fopen($fullPath, 'r');
                if ($handle) {
                    while (!feof($handle)) {
                        $line = fgets($handle);
                        if ($line !== false && trim($line) !== '') {
                            $lineCount++;
                        }
                    }
                    fclose($handle);
                    $totalRows = max(0, $lineCount - 1); // subtract header
                }
            } else {
                $reader = IOFactory::createReaderForFile($fullPath);
                $reader->setReadDataOnly(true);
                if (method_exists($reader, 'listWorksheetInfo')) {
                    $info = $reader->listWorksheetInfo($fullPath);
                    if (!empty($info[0]['totalRows'])) {
                        $totalRows = max(0, $info[0]['totalRows'] - 1);
                    }
                }
                if ($totalRows <= 0) {
                    $spreadsheet = $reader->load($fullPath);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $totalRows = max(0, $worksheet->getHighestDataRow() - 1);
                    $spreadsheet->disconnectWorksheets();
                    unset($spreadsheet);
                }
                unset($reader);
            }
        } catch (\Exception $e) {
            $totalRows = 1; // Fallback
        }

        if ($totalRows <= 0) {
            return back()->with('error', 'The uploaded file is empty or missing data rows.');
        }

        $history = UploadHistory::create([
            'file_name' => $originalName,
            'target_role' => $request->target_role,
            'status' => 'processing',
            'total_rows' => $totalRows,
            'processed_rows' => 0,
            'uploaded_by' => Auth::id(),
        ]);

        // Synchronous import
        try {
            $import = new UsersImport($history->id, $request->target_role);
            Excel::import($import, $fullPath);
            
            $accumulatedErrors = $import->getAccumulatedErrors();
            $history->refresh();
            
            $history->update([
                'status' => 'completed',
                'processed_rows' => $history->total_rows,
                'error_message' => !empty($accumulatedErrors) ? json_encode($accumulatedErrors) : ($history->error_message ?? null),
            ]);
            
            $errors = json_decode($history->error_message, true) ?? $accumulatedErrors ?? [];
            $errorCount = count($errors);
            if (isset($errors['__truncated'])) {
                $errorCount--;
            }
            $successCount = max(0, $history->total_rows - $errorCount);
            
            // Invalidate all aggregated stats to reflect newly imported accounts immediately
            \App\Services\CacheService::flushAll();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => $history->status === 'completed' && $errorCount === 0,
                    'imported' => $successCount,
                    'errors' => $errors,
                    'history' => $history,
                ]);
            }

            if ($errorCount > 0) {
                return back()
                    ->with('warning', "Upload completed with validation notes: {$successCount} records imported successfully, {$errorCount} rows failed.")
                    ->with('import_errors', $errors)
                    ->with('import_summary', [
                        'total' => $history->total_rows,
                        'success' => $successCount,
                        'failed' => $errorCount,
                        'history_id' => $history->id,
                        'role' => $request->target_role
                    ]);
            }

            return back()->with('success', "Bulk upload completed successfully! All {$history->total_rows} records processed.");
        } catch (\Exception $e) {
            $history->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'imported' => 0,
                    'errors' => [$e->getMessage()],
                    'message' => 'Failed to import: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $histories = UploadHistory::with('uploader')->latest()->get();
        return view('bulk-upload.history', compact('histories'));
    }

    public function progress()
    {
        $activeUploads = UploadHistory::whereIn('status', ['pending', 'processing'])
                            ->where('uploaded_by', Auth::id())
                            ->get();
        
        return response()->json($activeUploads);
    }

    public function downloadTemplate(Request $request)
    {
        $role = $request->query('role');
        return Excel::download(new \App\Exports\BulkUploadTemplateExport($role), 'bulk_upload_template.xlsx');
    }

    public function downloadErrorsExcel($id)
    {
        $history = UploadHistory::with('uploader')->findOrFail($id);
        $errors = json_decode($history->error_message, true) ?? [];
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Validation Errors');

        // Header formatting
        $sheet->setCellValue('A1', 'Row #');
        $sheet->setCellValue('B1', $history->target_role === 'stu' ? 'Register Number' : 'Employee ID');
        $sheet->setCellValue('C1', 'Validation Error Reason');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0F766E'], // Deep Teal / Emerald
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => 'FF0D9488'],
                ],
            ],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $rowIdx = 2;
        foreach ($errors as $key => $error) {
            if ($key === '__truncated') {
                $sheet->setCellValue('A' . $rowIdx, 'NOTE');
                $sheet->setCellValue('B' . $rowIdx, 'SYSTEM');
                $sheet->setCellValue('C' . $rowIdx, $error);
                $sheet->getStyle("A{$rowIdx}:C{$rowIdx}")->getFont()->setItalic(true)->getColor()->setARGB('FFDC2626');
                $rowIdx++;
                continue;
            }

            $rowNum = $rowIdx;
            $idVal = 'N/A';
            $desc = $error;

            if (preg_match('/^Row\s+(\d+)\s*\(([^:]+):\s*([^)]+)\):\s*(.+)$/i', $error, $matches)) {
                $rowNum = $matches[1];
                $idVal = trim($matches[3]);
                $desc = trim($matches[4]);
            } elseif (preg_match('/^Row\s+(\d+)\s*\(([^)]+)\):\s*(.+)$/i', $error, $matches)) {
                $rowNum = $matches[1];
                $idVal = trim($matches[2]);
                $desc = trim($matches[3]);
            }

            $sheet->setCellValue('A' . $rowIdx, $rowNum);
            $sheet->setCellValue('B' . $rowIdx, $idVal);
            $sheet->setCellValue('C' . $rowIdx, $desc);

            // Row zebra striping
            if ($rowIdx % 2 === 0) {
                $sheet->getStyle("A{$rowIdx}:C{$rowIdx}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF8FAFC');
            }

            // Cell border
            $sheet->getStyle("A{$rowIdx}:C{$rowIdx}")->getBorders()->getBottom()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                ->getColor()->setARGB('FFE2E8F0');

            $sheet->getRowDimension($rowIdx)->setRowHeight(24);
            $rowIdx++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(85);
        $sheet->getStyle('C2:C' . ($rowIdx - 1))->getAlignment()->setWrapText(true);

        $fileName = 'Bulk_Upload_Errors_' . ucfirst($history->target_role ?? 'Data') . '_' . date('Y_m_d_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function downloadErrorsWord($id)
    {
        $history = UploadHistory::with('uploader')->findOrFail($id);
        $errors = json_decode($history->error_message, true) ?? [];
        
        $roleName = match($history->target_role) {
            'stu' => 'Students',
            'sta' => 'Staff / Faculty',
            'admin' => 'Department Coordinators',
            default => strtoupper($history->target_role)
        };

        $fileName = 'Bulk_Upload_Errors_' . ucfirst($history->target_role) . '_' . date('Y-m-d_His') . '.doc';

        return response()->view('bulk-upload.errors_word', compact('history', 'errors', 'roleName'), 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
