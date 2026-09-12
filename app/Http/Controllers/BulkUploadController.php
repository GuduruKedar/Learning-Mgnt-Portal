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
        // Bulk imports can be large – remove the PHP time limit for this request only
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'target_role' => 'required|in:admin,sta,stu', // admin=Coordinator, sta=Staff, stu=Student
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/bulk', $fileName, 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Count total rows
        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $totalRows = $worksheet->getHighestDataRow() - 1; // subtract header

        if ($totalRows <= 0) {
            return back()->with('error', 'The uploaded file is empty or missing data rows.');
        }

        $history = UploadHistory::create([
            'file_name' => $file->getClientOriginalName(),
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
            
            $history->refresh();
            $history->update([
                'status' => 'completed',
                'processed_rows' => $history->total_rows,
            ]);
            
            $errors = json_decode($history->error_message, true);
            if ($errors && count($errors) > 0) {
                return back()->with('success', 'File processed, but some rows had validation errors.')->with('import_errors', $errors);
            }

            return back()->with('success', 'File uploaded and processed successfully.');
        } catch (\Exception $e) {
            $history->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
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
}
