<?php

namespace App\Helpers;

class RegisterNumberParser
{
    protected static $courseMapping = [
        'A' => 'B.Tech',
        'B' => 'M.Tech',
        'C' => 'MBA',
        'D' => 'MCA',
        'E' => 'Diploma',
        'G' => 'PHD',
        'J' => 'BCA',
        'K' => 'BBA',
        'M' => 'BSC',
        'N' => 'B.Pharmacy',
        'P' => 'M.PHARM',
        'P1' => 'phd full time',
        'P2' => 'phd part time',
        'Q' => 'M.A',
        'S' => 'M.Sc.',
        'T' => 'B.Ed',
        'U' => 'B.A (LLB)',
        'V' => 'B.B.A (LLB)',
        'W' => 'B.Sc Hons',
        'X' => 'PHARM.D',
    ];

    protected static $departmentMapping = [
        // A: B.Tech Programs
        'A' => [
            '01' => 'Biotechnology',
            '02' => 'Chemical Engineering',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information Technology',
            '08' => 'Mechanical Engineering',
            '11' => 'Textile Technology',
            '12' => 'Agricultural Engineering',
            '14' => 'Bioinformatics',
            '15' => 'Food Technology',
            '16' => 'Biomedical Engineering',
            '18' => 'Advanced Computer Science and Engineering', // AI & ML
            '19' => 'Advanced Computer Science and Engineering', // Cyber Security
            '20' => 'Advanced Computer Science and Engineering', // CSBS
            '21' => 'Mechanical Engineering',                  // Robotics and Automation
            '22' => 'Textile Technology',
            '23' => 'Advanced Computer Science and Engineering', // Data Science
            '24' => 'Advanced Computer Science and Engineering', // IoT
            '25' => 'Electronics and Communication Engineering', // VLSI
            '26' => 'Textile Technology',                       // Technical Textiles
        ],

        // B: M.Tech Programs
        'B' => [
            '01' => 'Biotechnology',
            '04' => 'Computer Science and Engineering',
            '08' => 'Mechanical Engineering',
            '11' => 'Electronics and Communication Engineering', // VLSI
            '12' => 'Food Technology',                           // Food Processing
            '15' => 'Civil Engineering',                         // Sustainable Smart Construction
            '21' => 'Agricultural Engineering',                  // Farm Machinery
            '23' => 'Advanced Computer Science and Engineering', // Data Science
            '24' => 'Electronics and Communication Engineering', // IoT
            '25' => 'Electrical and Electronics Engineering',    // Autonomous Electric Vehicles
            '26' => 'Advanced Computer Science and Engineering', // AI and Data Science
            '27' => 'Mechanical Engineering',                    // Smart Manufacturing
            '28' => 'Electrical and Electronics Engineering',    // Autonomous Electric Vehicles
            '29' => 'Civil Engineering',                         // Sustainable Smart Construction
        ],

        // C: MBA
        'C' => [
            '01' => 'Department of Management Studies',
        ],

        // D: MCA
        'D' => [
            '01' => 'Computer Applications',
        ],

        // E: Diploma
        'E' => [
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '08' => 'Mechanical Engineering',
        ],

        // G: PhD
        'G' => [
            '01' => 'Biotechnology',
            '02' => 'Chemical Engineering',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information Technology',
            '08' => 'Mechanical Engineering',
            '11' => 'Textile Technology',
            '12' => 'Department of Management Studies',
            '15' => 'Food Technology',
            '16' => 'Biomedical Engineering',
            '18' => 'Agricultural Engineering',
            '29' => 'Institute of Law',
            '30' => 'Physics',
            '31' => 'Chemistry',
            '32' => 'Mathematics and Statistics',
            '33' => 'Department of English and Other Indian & Foreign Languages',
            '34' => 'Social Sciences & Humanities',
            '35' => 'Computer Applications',
            '36' => 'Pharmaceutical Sciences',
            '37' => 'Advanced Computer Science and Engineering',
        ],

        // P1: PhD Full Time
        'P1' => [
            '01' => 'Biotechnology',
            '02' => 'Chemical Engineering',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information Technology',
            '08' => 'Mechanical Engineering',
            '11' => 'Textile Technology',
            '12' => 'Department of Management Studies',
            '15' => 'Food Technology',
            '16' => 'Biomedical Engineering',
            '18' => 'Agricultural Engineering',
            '29' => 'Institute of Law',
            '30' => 'Physics',
            '31' => 'Chemistry',
            '32' => 'Mathematics and Statistics',
            '33' => 'Department of English and Other Indian & Foreign Languages',
            '34' => 'Social Sciences & Humanities',
            '35' => 'Computer Applications',
            '36' => 'Pharmaceutical Sciences',
            '37' => 'Advanced Computer Science and Engineering',
        ],

        // P2: PhD Part Time
        'P2' => [
            '01' => 'Biotechnology',
            '02' => 'Chemical Engineering',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information Technology',
            '08' => 'Mechanical Engineering',
            '11' => 'Textile Technology',
            '12' => 'Department of Management Studies',
            '15' => 'Food Technology',
            '16' => 'Biomedical Engineering',
            '18' => 'Agricultural Engineering',
            '29' => 'Institute of Law',
            '30' => 'Physics',
            '31' => 'Chemistry',
            '32' => 'Mathematics and Statistics',
            '33' => 'Department of English and Other Indian & Foreign Languages',
            '34' => 'Social Sciences & Humanities',
            '35' => 'Computer Applications',
            '36' => 'Pharmaceutical Sciences',
            '37' => 'Advanced Computer Science and Engineering',
        ],

        // J: BCA
        'J' => [
            '01' => 'Computer Applications',
        ],

        // K: BBA
        'K' => [
            '01' => 'Department of Management Studies',
        ],

        // M: B.Sc Programs
        'M' => [
            '01' => 'Mathematics and Statistics',                 // B.Sc MSCS
            '02' => 'Social Sciences & Humanities',               // B.Sc Psychology
            '03' => 'Mathematics and Statistics',                 // B.Sc Actuarial Science
            '04' => 'Advanced Computer Science and Engineering',  // B.Sc Data Science
        ],

        // N: B.Pharmacy
        'N' => [
            '01' => 'Pharmaceutical Sciences',
        ],

        // P: M.Pharm
        'P' => [
            '01' => 'Pharmaceutical Sciences',
        ],

        // Q: M.A
        'Q' => [
            '01' => 'Department of English and Other Indian & Foreign Languages',
        ],

        // S: M.Sc Programs
        'S' => [
            '01' => 'Chemistry',                                  // M.Sc Chemistry
            '02' => 'Chemistry',                                  // M.Sc Organic Chemistry
            '03' => 'Advanced Computer Science and Engineering',  // M.Sc Data Science
            '05' => 'Social Sciences & Humanities',               // M.Sc Psychology
        ],

        // T: Education
        'T' => [
            '01' => 'Department of Education',                    // B.A. B.Ed.
            '02' => 'Department of Education',                    // B.Sc. B.Ed.
        ],

        // U: BA LLB
        'U' => [
            '01' => 'Institute of Law',
        ],

        // V: BBA LLB
        'V' => [
            '01' => 'Institute of Law',
        ],

        // W: B.Sc (Hons) Agriculture
        'W' => [
            '01' => 'Vignan Institute of Agriculture and Technology',
        ],

        // X: Pharm.D
        'X' => [
            '01' => 'Pharmaceutical Sciences',
        ],
    ];

    /**
     * Parses a student registration number.
     * Extracts Course and Department information.
     */
    public static function parse($registerNumber)
    {
        $registerNumber = strtoupper(trim($registerNumber));
        
        if (!preg_match('/^\d{2}[A-Z0-9]{2}[A-Z0-9]{1,2}\d+$/', $registerNumber)) {
            return null; // Invalid structure (must start with 2 digits, end with digits)
        }
        
        $courseCode = null;
        $deptCode = null;
        
        // P1 and P2 are 2 chars long, others are 1 char long
        if (strlen($registerNumber) >= 10) {
            $c2 = substr($registerNumber, 4, 2);
            if (isset(self::$courseMapping[$c2])) {
                $courseCode = $c2;
                $deptCode = substr($registerNumber, 6, 2);
            } else {
                $c1 = substr($registerNumber, 4, 1);
                if (isset(self::$courseMapping[$c1])) {
                    $courseCode = $c1;
                    $deptCode = substr($registerNumber, 5, 2);
                }
            }
        }
        
        if (!$courseCode || !$deptCode) {
            return null;
        }
        
        if (!isset(self::$courseMapping[$courseCode])) {
            return null; // Invalid course code
        }
        
        $courseName = self::$courseMapping[$courseCode];
        $deptName = self::$departmentMapping[$courseCode][$deptCode] ?? null;

        if (!$deptName) {
            return null; // Invalid department code for this course
        }

        return [
            'course_code' => $courseCode,
            'course_name' => $courseName,
            'department_code' => $deptCode,
            'department_name' => $deptName,
        ];
    }
}
