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
        'H' => 'BBM',
        'I' => 'B.A (Hons.)',
        'J' => 'BCA',
        'K' => 'BBA',
        'M' => 'BSC',
        'N' => 'B.Pharmacy',
        'P' => 'M.PHARM',
        'P1' => 'phd full time',
        'P2' => 'phd part time',
        'Q' => 'M.A',
        'R' => 'BBM+MBA',
        'S' => 'M.Sc.',
        'T' => 'M.Phil',
        'U' => 'B.A (LLB)',
        'V' => 'B.B.A (LLB)',
        'W' => 'B.Sc Hons',
        'X' => 'PHARM.D',
        'Y' => 'B.Com'
    ];

    protected static $departmentMapping = [
        'A' => [
            '01' => 'Bio Technology',
            '02' => 'Chemical Engg',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science & Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information technology',
            '08' => 'Mechanical Engineering',
            '12' => 'Agriculture Engineering',
            '13' => 'Electronics and Computer Engineering',
            '14' => 'Bioinformatics',
            '15' => 'Food Technology',
            '16' => 'Bio. Medical Engineering',
            '18' => 'CSE- ARTIFICIAL INTELLIGENCE AND MACHINE LEARNING',
            '19' => 'CSE- CYBER SECURITY',
            '20' => 'COMPUTER SCIENCE AND BUSINESS SYSTEMS',
            '21' => 'ROBOTICS AND AUTOMATION',
            '23' => 'Data Science',
            '24' => 'CSE-Internet Of Things',
            '25' => 'ECE-VLSI',
        ],
        'B' => [
            '01' => 'Biotechnology and Bioprocess Engineering',
            '02' => 'Chemical Enginneering',
            '03' => 'Communication and Signal Processing',
            '04' => 'Computer Science & Engineering',
            '05' => 'Digital Electronics and Communication System',
            '06' => 'Embedded Systems',
            '07' => 'Energy Engineering',
            '09' => 'Machine Design',
            '10' => 'Power Electronics and Drives',
            '11' => 'Very Large Scale Integration (VLSI)',
            '12' => 'Food Processing Technology',
            '13' => 'Power Systems',
            '14' => 'cad/cam/cae',
            '15' => 'civil Engineering',
            '16' => 'Computer networks & information security',
            '17' => 'Thermal Engineering',
            '18' => 'machine learning',
            '19' => 'Data Communication & Networking',
            '20' => 'Petroleum Engineering',
            '21' => 'Farm Machinery',
            '22' => 'Image Processing & Machine Vision',
            '23' => 'Data Science',
            '24' => 'IOT',
            '25' => 'Electrical Vehicle Technology',
            '26' => 'Artificial Intelligence and Data Science',
            '27' => 'Smart Manufacuring',
            '28' => 'Autonomous Electric Vehicles',
            '29' => 'Sustainable Smart Construction'
        ],
        'C' => [
            '01' => 'Master of Business Administration',
            '02' => 'Human Resourse',
            '03' => 'Marketing',
            '04' => 'Finance'
        ],
        'D' => [
            '01' => 'Master of Computer Applications'
        ],
        'E' => [
            '04' => 'COMPUTER SCIENCE & ENGINEERING',
            '05' => 'ELECTRONICS & COMMUNICATION ENGINEERING',
            '06' => 'EEE',
            '07' => 'Artificial Intelligence and Machine Learning',
            '08' => 'Mechanical'
        ],
        'G' => [
            '01' => 'biotech',
            '02' => 'Chemical',
            '03' => 'civil',
            '04' => 'computer science engineering',
            '05' => 'Electronics and communications engineering',
            '06' => 'electrical and electronics engineering',
            '07' => 'information technology',
            '08' => 'mechanical engineering',
            '12' => 'Management',
            '30' => 'physics',
            '31' => 'chemistry',
            '32' => 'Mathematics',
            '33' => 'English',
            '35' => 'Computer Application'
        ],
        'H' => [
            '01' => 'BACHELOR OF BUSINESS MANAGEMENT'
        ],
        'I' => [
            '01' => 'B.A (Hons.) Political Science'
        ],
        'J' => [
            '01' => 'BCA'
        ],
        'K' => [
            '01' => 'BACHELOR OF BUSINESS ADMINISTRATION'
        ],
        'M' => [
            '01' => 'BSC',
            '02' => 'BSC-PSYCHOLOGY',
            '03' => 'BSC-ACTUARIAL SCIENCE',
            '04' => 'DATA SCIENCE'
        ],
        'N' => [
            '01' => 'BPharmacy'
        ],
        'P' => [
            '01' => 'MASTER OF PHARMACY'
        ],
        'P1' => [
            '01' => 'biotech',
            '02' => 'Chemical',
            '03' => 'civil',
            '04' => 'computer science engineering',
            '05' => 'Electronics and communications engineering',
            '06' => 'electrical and electronics engineering',
            '07' => 'information technology',
            '08' => 'mechanical engineering',
            '11' => 'TEXTILE TECHNOLOGY',
            '12' => 'Management',
            '15' => 'Food Technology',
            '18' => 'Agriculture',
            '19' => 'Agronomy',
            '29' => 'LAW',
            '30' => 'physics',
            '31' => 'chemistry',
            '32' => 'Mathematics',
            '33' => 'English',
            '35' => 'Computer Application',
            '36' => 'Pharmaceutical Science',
            '37' => 'Artificial Intelligence & Machine Learning',
            '38' => 'Cyber Security',
            '39' => 'Data Science'
        ],
        'P2' => [
            '01' => 'biotech',
            '02' => 'Chemical',
            '03' => 'civil',
            '04' => 'computer science engineering',
            '05' => 'Electronics and communications engineering',
            '06' => 'electrical and electronics engineering',
            '07' => 'information technology',
            '08' => 'mechanical engineering',
            '11' => 'TT',
            '12' => 'Management',
            '15' => 'Food Technology',
            '18' => 'Agriculture',
            '19' => 'Agronomy',
            '29' => 'LAW',
            '30' => 'physics',
            '31' => 'chemistry',
            '32' => 'Mathematics',
            '33' => 'English',
            '35' => 'Computer Application',
            '36' => 'Pharmaceutical Science',
            '37' => 'Artificial Intelligence & Machine Learning',
            '38' => 'Cyber Security',
            '39' => 'Data Science'
        ],
        'Q' => [
            '01' => 'ENGLISH'
        ],
        'R' => [
            '01' => 'intrgrated bbm+mba'
        ],
        'S' => [
            '01' => 'Chemistry',
            '02' => 'Organic Chemistry',
            '03' => 'Data Science',
            '04' => 'Pharmaceutical Chemistry',
            '05' => 'PSYCHOLOGY',
            '06' => 'MSC-Agronomy',
            '07' => 'MSC-Entomology',
            '08' => 'MSC-Soil Science',
            '09' => 'MSC-Agricultural Economics',
            '10' => 'MSC-Vegetable Science',
            '11' => 'MSC-Floriculture and Landscaping',
            '12' => 'MSC-Plant Pathology',
            '13' => 'MSC-Genetics and Plant Breeding'
        ],
        'T' => [
            '01' => 'B.A. B.Ed. English Literature',
            '02' => 'B.A. B.Ed. Economics',
            '03' => 'B.Sc. B.Ed. Botany',
            '04' => 'B.Sc. B.Ed. Zoology',
            '05' => 'B.Sc. B.Ed. Mathematics',
            '06' => 'B.Sc. B.Ed. Chemistry'
        ],
        'U' => [
            '01' => 'B.A (LLB)'
        ],
        'V' => [
            '01' => 'B.B.A (LLB)'
        ],
        'W' => [
            '01' => 'AGRICULTURE',
            '02' => 'DATA SCIENCE'
        ],
        'X' => [
            '01' => 'DOCTOR OF PHARMACY'
        ]
    ];

    /**
     * Parses a student registration number.
     * Extracts Course and Department information.
     */
    public static function parse($registerNumber)
    {
        $registerNumber = strtoupper(trim($registerNumber));
        
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
