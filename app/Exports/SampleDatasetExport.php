<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleDatasetExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $count;
    protected $role;

    protected $firstNames = [
        'Aarav', 'Vivaan', 'Aditya', 'Vihaan', 'Arjun', 'Sai', 'Reyansh', 'Ayaan', 'Krishna', 'Ishaan',
        'Shaurya', 'Atharv', 'Advik', 'Pranav', 'Advaith', 'Aaryan', 'Dhruv', 'Kabir', 'Ritvik', 'Darsh',
        'Ananya', 'Diya', 'Gauri', 'Isha', 'Kavya', 'Khushi', 'Myra', 'Navya', 'Pari', 'Prisha',
        'Riya', 'Saanvi', 'Samaira', 'Sara', 'Siya', 'Sneha', 'Tanvi', 'Vanya', 'Zoya', 'Meera',
        'Rohan', 'Rahul', 'Kiran', 'Nikhil', 'Pawan', 'Suresh', 'Manish', 'Vikram', 'Deepak', 'Tarun',
        'Pooja', 'Divya', 'Bhavna', 'Harini', 'Lavanya', 'Manasa', 'Deepika', 'Keerthi', 'Swathi', 'Sravani',
        'Karthik', 'Sanjay', 'Manoj', 'Chaitanya', 'Harish', 'Ganesh', 'Varun', 'Avinash', 'Teja', 'Charan',
        'Anusha', 'Sandhya', 'Pallavi', 'Sirisha', 'Geetha', 'Sireesha', 'Radhika', 'Pavani', 'Roopa', 'Sujatha'
    ];

    protected $lastNames = [
        'Sharma', 'Verma', 'Gupta', 'Malhotra', 'Bhatia', 'Saxena', 'Kapoor', 'Reddy', 'Rao', 'Chowdary',
        'Goud', 'Naidu', 'Patel', 'Shah', 'Mehta', 'Joshi', 'Kulkarni', 'Deshmukh', 'Nair', 'Menon',
        'Pillai', 'Iyer', 'Iyengar', 'Mukherjee', 'Banerjee', 'Chatterjee', 'Das', 'Sen', 'Ghosh', 'Dey',
        'Singh', 'Kaur', 'Kumar', 'Prasad', 'Mishra', 'Pandey', 'Dubey', 'Tiwari', 'Shukla', 'Yadav',
        'Venkatesh', 'Guduru', 'Kondapalli', 'Bandaru', 'Chaganti', 'Duggirala', 'Emani', 'Guntupalli', 'Jasti', 'Kakani'
    ];

    public function __construct($count = 500, $role = 'stu')
    {
        $this->count = max(1, (int)$count);
        $this->role = $role ?: 'stu';
    }

    public function headings(): array
    {
        if ($this->role === 'stu') {
            return [
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
        } else {
            return [
                'school',
                'department',
                'designation',
                'employee_id',
                'firstname',
                'middlename',
                'lastname',
                'photo',
                'email',
                'mobile_number',
                'password'
            ];
        }
    }

    public function array(): array
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $isCoordinator = $user && ($user->role === 'admin' || ($user->profile && $user->profile->roles_id === 'admin'))
            && $user->role !== 'ssh_admin' && $user->role !== 'sa'
            && (!isset($user->profile->roles_id) || ($user->profile->roles_id !== 'ssh_admin' && $user->profile->roles_id !== 'sa'));
        
        $coordSchoolCode = $isCoordinator && $user->profile ? $user->profile->schools_id : null;
        $coordDeptCode = $isCoordinator && $user->profile ? $user->profile->departments_id : null;

        $schools = \App\Models\School::all()->keyBy('code');
        $departments = \App\Models\Department::all()->keyBy('code');
        $programs = \App\Models\Program::all();

        // Department mapping for student reg numbers
        $deptMapping = [
            'dep_bio' => ['course' => 'A', 'code' => '01', 'level' => 'UG', 'program' => 'Bio Technology', 'dept' => 'Bio Technology', 'school' => 'sc_bps'],
            'dep_che' => ['course' => 'A', 'code' => '02', 'level' => 'UG', 'program' => 'Chemical Engineering', 'dept' => 'Chemical Engineering', 'school' => 'sc_ceng'],
            'dep_civ' => ['course' => 'A', 'code' => '03', 'level' => 'UG', 'program' => 'Civil Engineering', 'dept' => 'Civil Engineering', 'school' => 'sc_ceng'],
            'dep_cse' => ['course' => 'A', 'code' => '04', 'level' => 'UG', 'program' => 'Computer Science & Engineering', 'dept' => 'Computer Science & Engineering', 'school' => 'sc_ci'],
            'dep_ece' => ['course' => 'A', 'code' => '05', 'level' => 'UG', 'program' => 'Electronics and Communication Engineering', 'dept' => 'Electronics and Communication Engineering', 'school' => 'sc_eeceng'],
            'dep_eee' => ['course' => 'A', 'code' => '06', 'level' => 'UG', 'program' => 'Electrical and Electronics Engineering', 'dept' => 'Electrical and Electronics Engineering', 'school' => 'sc_eeceng'],
            'dep_it' => ['course' => 'A', 'code' => '07', 'level' => 'UG', 'program' => 'Information Technology', 'dept' => 'Information Technology', 'school' => 'sc_ci'],
            'dep_mech' => ['course' => 'A', 'code' => '08', 'level' => 'UG', 'program' => 'Mechanical Engineering', 'dept' => 'Mechanical Engineering', 'school' => 'sc_ceng'],
            'dep_agri' => ['course' => 'A', 'code' => '12', 'level' => 'UG', 'program' => 'Agriculture Engineering', 'dept' => 'Agriculture Engineering', 'school' => 'sc_aft'],
            'dep_bioinfo' => ['course' => 'A', 'code' => '14', 'level' => 'UG', 'program' => 'Bioinformatics', 'dept' => 'Bioinformatics', 'school' => 'sc_bps'],
            'dep_foodtech' => ['course' => 'A', 'code' => '15', 'level' => 'UG', 'program' => 'Food Technology', 'dept' => 'Food Technology', 'school' => 'sc_aft'],
            'dep_bme' => ['course' => 'A', 'code' => '16', 'level' => 'UG', 'program' => 'Biomedical Engineering', 'dept' => 'Biomedical Engineering', 'school' => 'sc_bps'],
            'dep_acse' => ['course' => 'A', 'code' => '18', 'level' => 'UG', 'program' => 'Advanced Computer Science and Engineering', 'dept' => 'Advanced Computer Science and Engineering', 'school' => 'sc_ci'],
            'dep_ca' => ['course' => 'J', 'code' => '01', 'level' => 'UG', 'program' => 'Computer Applications', 'dept' => 'Computer Applications', 'school' => 'sc_ci'],
            'dep_mba' => ['course' => 'C', 'code' => '01', 'level' => 'PG', 'program' => 'Management Studies', 'dept' => 'Management Studies', 'school' => 'sc_lm'],
            'dep_ps' => ['course' => 'N', 'code' => '01', 'level' => 'UG', 'program' => 'Pharmaceutical Sciences', 'dept' => 'Pharmaceutical Sciences', 'school' => 'sc_bps'],
        ];

        // If coordinator, ONLY use their department
        $activeDepts = [];
        if ($coordDeptCode && isset($deptMapping[$coordDeptCode])) {
            $activeDepts[] = $deptMapping[$coordDeptCode];
        } elseif ($coordDeptCode && isset($departments[$coordDeptCode])) {
            $deptObj = $departments[$coordDeptCode];
            $schoolObj = isset($schools[$deptObj->school_id]) ? $schools[$deptObj->school_id] : null;
            $activeDepts[] = [
                'course' => 'A',
                'code' => '07',
                'level' => 'UG',
                'program' => $deptObj->name,
                'dept' => $deptObj->name,
                'school' => $schoolObj ? $schoolObj->code : 'sc_ci'
            ];
        } else {
            // Superadmin: use diverse top departments
            $activeDepts = [
                $deptMapping['dep_cse'],
                $deptMapping['dep_acse'],
                $deptMapping['dep_it'],
                $deptMapping['dep_ece'],
                $deptMapping['dep_mech'],
                $deptMapping['dep_bio'],
            ];
        }

        $records = [];
        $deptCount = count($activeDepts);

        $yearPrefix = '25';
        $serialTracker = [];

        for ($i = 1; $i <= $this->count; $i++) {
            $deptConfig = $activeDepts[($i - 1) % $deptCount];
            
            $schoolCode = $deptConfig['school'];
            $schoolName = isset($schools[$schoolCode]) ? $schools[$schoolCode]->name : $schoolCode;
            
            $deptName = $deptConfig['dept'];
            $level = $deptConfig['level'];
            $progName = $deptConfig['program'];

            $courseLetter = $deptConfig['course'];
            $deptSubCode = $deptConfig['code'];

            if (!isset($serialTracker[$deptSubCode])) {
                $serialTracker[$deptSubCode] = 1;
            }
            $serial = $serialTracker[$deptSubCode]++;

            $intakeCode = (int)(($serial - 1) / 900) + 1;
            $rollNo = (($serial - 1) % 900) + 1;

            // Generate a strictly 10-char register number e.g. 251FA04001, 251FA07001
            $regNum = sprintf('%s%dF%s%s%03d', $yearPrefix, $intakeCode, $courseLetter, $deptSubCode, $rollNo);

            $fn = $this->firstNames[($i * 7) % count($this->firstNames)];
            $ln = $this->lastNames[($i * 13) % count($this->lastNames)];
            
            // Ensure first and last name are not identical
            if (strtolower($fn) === strtolower($ln)) {
                $ln = $this->lastNames[(($i * 13) + 3) % count($this->lastNames)];
            }

            $email = strtolower($fn . '.' . $ln . $serial . '@vignan.ac.in');
            $phone = sprintf('987%07d', ($i * 97 + 100000) % 9000000 + 1000000);
            $password = 'Student#963';

            if ($this->role === 'stu') {
                $records[] = [
                    $schoolName,
                    $deptName,
                    $level,
                    $progName,
                    $regNum,
                    $fn,
                    '',
                    $ln,
                    '',
                    $email,
                    $phone,
                    $password
                ];
            } else {
                $empId = sprintf('%05d', 70000 + $i);
                $records[] = [
                    $schoolName,
                    $deptName,
                    'Assistant Professor',
                    $empId,
                    $fn,
                    '',
                    $ln,
                    '',
                    $email,
                    $phone,
                    'Staff@852'
                ];
            }
        }

        return $records;
    }

    public function title(): string
    {
        return 'Sample Dataset (' . $this->count . ' Records)';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->role === 'stu' ? 'FF4F46E5' : 'FF0D9488']
                ]
            ],
        ];
    }
}
