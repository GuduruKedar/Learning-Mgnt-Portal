<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\User;
use App\Models\Profile;

// Fetch all existing usernames, emails, and phones to avoid any conflict
$existingUsernames = array_flip(User::pluck('username')->map(fn($v) => strtoupper(trim($v)))->toArray());
$existingEmails = array_flip(Profile::whereNotNull('email')->pluck('email')->map(fn($v) => strtolower(trim($v)))->toArray());
$existingPhones = array_flip(Profile::whereNotNull('phone')->pluck('phone')->map(fn($v) => trim($v))->toArray());

$firstNames = [
    "Aarav", "Vivaan", "Aditya", "Vihaan", "Arjun", "Sai", "Reyansh", "Ayaan", "Krishna", "Ishaan",
    "Shaurya", "Atharva", "Advik", "Pranav", "Advaith", "Aaryan", "Dhruv", "Kabir", "Ritvik", "Darsh",
    "Ananya", "Diya", "Saanvi", "Aadhya", "Pari", "Kiara", "Myra", "Riya", "Anushka", "Aarohi",
    "Isha", "Navya", "Avani", "Tanvi", "Shanaya", "Pooja", "Sneha", "Kavya", "Bhavya", "Deepika",
    "Rohan", "Rahul", "Karthik", "Varun", "Nikhil", "Sanjay", "Vikram", "Harsh", "Abhishek", "Manish",
    "Praveen", "Suresh", "Ramesh", "Naresh", "Ganesh", "Mahesh", "Dinesh", "Rajesh", "Kiran", "Tarun",
    "Swathi", "Divya", "Harini", "Keerthi", "Lavanya", "Meghana", "Mounika", "Nandini", "Pallavi", "Pavani",
    "Pranathi", "Ramya", "Sandhya", "Sirisha", "Sowmya", "Sravani", "Srinu", "Tejaswi", "Vaishnavi", "Yamini"
];

$lastNames = [
    "Reddy", "Chowdhary", "Rao", "Varma", "Goud", "Naidu", "Sharma", "Verma", "Gupta", "Patel",
    "Mehta", "Joshi", "Bhat", "Nair", "Pillai", "Iyer", "Menon", "Das", "Banerjee", "Chatterjee",
    "Mukherjee", "Sen", "Bose", "Dutta", "Ghosh", "Mishra", "Pandey", "Tiwari", "Dubey", "Shukla",
    "Kumar", "Prasad", "Singh", "Yadav", "Chauhan", "Thakur", "Rathore", "Pawar", "Kadam", "Shinde",
    "Patil", "Deshmukh", "Kulkarni", "Jadhav", "More", "Bhosale", "Gaikwad", "Tamboli", "Sawant", "Salunkhe"
];

$middleNames = [
    "", "", "", "Kumar", "Chandra", "Prasad", "Raj", "Venkata", "Sri", "Sai", "Mohan", "Kishore", "Babu", ""
];

$batches = [
    [
        'school' => 'School of Computing and Informatics',
        'department' => 'Computer Science & Engineering',
        'level' => 'UG',
        'program' => 'Computer Science & Engineering',
        'prefix' => '251FA04',
        'count' => 100
    ],
    [
        'school' => 'School of Computing and Informatics',
        'department' => 'Advanced Computer Science and Engineering',
        'level' => 'UG',
        'program' => 'Artificial Intelligence and Machine Learning',
        'prefix' => '251FA18',
        'count' => 100
    ],
    [
        'school' => 'School of Computing and Informatics',
        'department' => 'Information Technology',
        'level' => 'UG',
        'program' => 'Information Technology',
        'prefix' => '251FA07',
        'count' => 100
    ],
    [
        'school' => 'School of Electrical, Electronics and Communication Engineering',
        'department' => 'Electronics and Communication Engineering',
        'level' => 'UG',
        'program' => 'Electronics and Communication Engineering',
        'prefix' => '251FA05',
        'count' => 100
    ],
    [
        'school' => 'School of Biotechnology and Pharmaceutical Sciences',
        'department' => 'Biotechnology',
        'level' => 'UG',
        'program' => 'Biotechnology',
        'prefix' => '251FA01',
        'count' => 50
    ],
    [
        'school' => 'School of Computing and Informatics',
        'department' => 'Computer Applications',
        'level' => 'PG',
        'program' => 'Master of Computer Applications',
        'prefix' => '251FD01',
        'count' => 50
    ]
];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Student Template Data');

$headers = [
    'school',
    'department',
    'level',
    'program',
    'register_number',
    'firstname',
    'middlename',
    'lastname',
    'photo',
    'email',
    'mobile_number',
    'password'
];

foreach ($headers as $colIdx => $header) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
    $sheet->setCellValue($colLetter . '1', $header);
}

$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1E293B']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
];
$sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
$sheet->getRowDimension(1)->setRowHeight(28);

$rowNum = 2;
$globalStudentCounter = 1;
$mobileSeq = 9980000001;

foreach ($batches as $batch) {
    $generatedInBatch = 0;
    $seq = 1;

    while ($generatedInBatch < $batch['count']) {
        $regSeq = str_pad($seq, 3, '0', STR_PAD_LEFT);
        $candidateReg = $batch['prefix'] . $regSeq;
        $seq++;

        // Skip if already in DB
        if (isset($existingUsernames[$candidateReg])) {
            continue;
        }

        $fName = $firstNames[($globalStudentCounter) % count($firstNames)];
        $lName = $lastNames[($globalStudentCounter * 7) % count($lastNames)];
        if (strtolower($fName) === strtolower($lName)) {
            $lName = $lastNames[($globalStudentCounter * 7 + 1) % count($lastNames)];
        }
        $mName = $middleNames[($globalStudentCounter) % count($middleNames)];

        // Generate unique email
        $candidateEmail = strtolower($fName . '.' . $lName . $globalStudentCounter . '@vignan.ac.in');
        while (isset($existingEmails[$candidateEmail])) {
            $globalStudentCounter++;
            $candidateEmail = strtolower($fName . '.' . $lName . $globalStudentCounter . '@vignan.ac.in');
        }

        // Generate unique phone
        $candidateMobile = (string)$mobileSeq;
        while (isset($existingPhones[$candidateMobile])) {
            $mobileSeq++;
            $candidateMobile = (string)$mobileSeq;
        }
        $mobileSeq++;

        $sheet->setCellValue('A' . $rowNum, $batch['school']);
        $sheet->setCellValue('B' . $rowNum, $batch['department']);
        $sheet->setCellValue('C' . $rowNum, $batch['level']);
        $sheet->setCellValue('D' . $rowNum, $batch['program']);
        $sheet->setCellValueExplicit('E' . $rowNum, $candidateReg, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('F' . $rowNum, $fName);
        $sheet->setCellValue('G' . $rowNum, $mName);
        $sheet->setCellValue('H' . $rowNum, $lName);
        $sheet->setCellValue('I' . $rowNum, '');
        $sheet->setCellValue('J' . $rowNum, $candidateEmail);
        $sheet->setCellValueExplicit('K' . $rowNum, $candidateMobile, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('L' . $rowNum, 'Student#963');

        $rowNum++;
        $globalStudentCounter++;
        $generatedInBatch++;
    }
}

foreach (range(1, count($headers)) as $col) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
}

$dirs = [
    __DIR__ . '/public/downloads',
    __DIR__ . '/storage/app/public/downloads'
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

$xlsxPath1 = __DIR__ . '/students_bulk_upload_500.xlsx';
$xlsxPath2 = __DIR__ . '/public/downloads/students_bulk_upload_500.xlsx';
$xlsxPath3 = __DIR__ . '/storage/app/public/downloads/students_bulk_upload_500.xlsx';

$csvPath1 = __DIR__ . '/students_bulk_upload_500.csv';
$csvPath2 = __DIR__ . '/public/downloads/students_bulk_upload_500.csv';

$writerXlsx = new Xlsx($spreadsheet);
$writerXlsx->save($xlsxPath1);
$writerXlsx->save($xlsxPath2);
$writerXlsx->save($xlsxPath3);

$writerCsv = new Csv($spreadsheet);
$writerCsv->save($csvPath1);
$writerCsv->save($csvPath2);

echo "Successfully generated 500 unique student records with explicit 'program' field:\n";
echo "- $xlsxPath1\n";
echo "- $xlsxPath2\n";
echo "- $csvPath1\n";
echo "- Total Rows: " . ($rowNum - 2) . "\n";
