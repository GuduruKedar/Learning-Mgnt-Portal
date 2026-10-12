<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$filePath = storage_path('app/public/uploads/bulk/1790789287_students_4000_correct_template.xlsx');

$import = new \App\Imports\UsersImport(9999, 'stu');

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
$spreadsheet = $reader->load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rawRows = $sheet->toArray(null, true, true, true);

$headers = array_map('strtolower', array_map('trim', $rawRows[1]));
$total = count($rawRows) - 1;

$passed = 0;
$failed = 0;
$reasons = [];

for ($r = 2; $r <= count($rawRows); $r++) {
    $rowAssociative = [];
    foreach ($headers as $colKey => $headerName) {
        $rowAssociative[$headerName] = $rawRows[$r][$colKey] ?? null;
    }
    
    $transformed = $import->prepareForValidation($rowAssociative, $r);
    $validator = \Illuminate\Support\Facades\Validator::make($transformed, $import->rules(), $import->customValidationMessages());
    
    if ($validator->fails()) {
        $failed++;
        foreach ($validator->errors()->all() as $err) {
            $errKey = preg_replace('/Row \d+/', 'Row X', $err);
            $errKey = preg_replace('/\'[^\']+\'/', '\'...\'', $errKey);
            $reasons[$errKey] = ($reasons[$errKey] ?? 0) + 1;
        }
    } else {
        $passed++;
    }
}

echo "Total Data Rows: $total\n";
echo "Passed Validation: $passed\n";
echo "Failed Validation: $failed\n";
echo "Failure Reasons Breakdown:\n";
arsort($reasons);
print_r($reasons);
