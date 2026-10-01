<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Imports\UsersImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

function testFile($filePath) {
    echo "========================================\n";
    echo "Testing file: $filePath\n";

    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    $header = array_shift($rows);
    $headerMap = array_map('strtolower', array_map('trim', $header));

    $import = new UsersImport(999999, 'stu');
    $validCount = 0;
    $errors = [];

    foreach ($rows as $index => $rowValues) {
        $row = [];
        $colIdx = 1;
        foreach ($rowValues as $val) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $key = $headerMap[$colLetter] ?? "col_$colIdx";
            $row[$key] = $val;
            $colIdx++;
        }

        $transformed = $import->prepareForValidation($row, $index);
        $validator = \Illuminate\Support\Facades\Validator::make($transformed, $import->rules(), $import->customValidationMessages());

        if ($validator->fails()) {
            $errors[] = [
                'row' => $index,
                'reg' => $row['register_number'] ?? 'unknown',
                'errors' => $validator->errors()->all()
            ];
        } else {
            $validCount++;
        }
    }

    echo "Results:\n";
    echo "- Total Rows checked: " . count($rows) . "\n";
    echo "- Valid Rows: $validCount\n";
    echo "- Failed Rows: " . count($errors) . "\n";

    if (!empty($errors)) {
        echo "First 5 errors:\n";
        print_r(array_slice($errors, 0, 5));
    } else {
        echo "ALL " . count($rows) . " ROWS PASSED VALIDATION PERFECTLY!\n";
    }
}

testFile(__DIR__ . '/public/downloads/students_bulk_upload_500.xlsx');
testFile(__DIR__ . '/public/downloads/students_bulk_upload_1000.xlsx');
