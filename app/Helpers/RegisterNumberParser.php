<?php

namespace App\Helpers;

use App\Models\Department;
use App\Models\School;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterNumberParser
{
    /**
     * In-memory cache for dynamic mappings.
     */
    protected static ?array $inMemoryDynamicMappings = null;

    /**
     * File path for storing dynamic course and department mappings.
     */
    protected static function getStoragePath(): string
    {
        try {
            if (function_exists('app') && app()->bound('path.storage')) {
                return storage_path('app/dynamic_register_mappings.json');
            }
        } catch (\Throwable $e) {
            // Fallback
        }
        return dirname(__DIR__, 2) . '/storage/app/dynamic_register_mappings.json';
    }

    /**
     * Base static course mapping (Degrees / Levels).
     */
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

    /**
     * Base static department mapping (Course Code => [Numeric Code => Department Name]).
     */
    protected static $departmentMapping = [
        'A' => [
            '01' => 'Biotechnology',
            '02' => 'Chemical Engineering',
            '03' => 'Civil Engineering',
            '04' => 'Computer Science and Engineering',
            '05' => 'Electronics and Communication Engineering',
            '06' => 'Electrical and Electronics Engineering',
            '07' => 'Information Technology',
            '08' => 'Mechanical Engineering',
            '09' => 'Electrical and Electronics Engineering',
            '10' => 'Information Technology',
            '11' => 'Textile Technology',
            '12' => 'Agricultural Engineering',
            '13' => 'Electronics and Communication Engineering',
            '14' => 'Bioinformatics',
            '15' => 'Food Technology',
            '16' => 'Biomedical Engineering',
            '17' => 'Computer Science and Engineering',
            '18' => 'Advanced Computer Science and Engineering',
            '19' => 'Advanced Computer Science and Engineering',
            '20' => 'Advanced Computer Science and Engineering',
            '21' => 'Mechanical Engineering',
            '22' => 'Advanced Computer Science and Engineering',
            '23' => 'Advanced Computer Science and Engineering',
            '24' => 'Advanced Computer Science and Engineering',
            '25' => 'Electronics and Communication Engineering',
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
     * Known school association dictionary for canonical departments.
     */
    protected static $knownDepartmentSchools = [
        // Computing & Informatics
        'Computer Science and Engineering' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_cse'],
        'Computer Science & Engineering' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_cse'],
        'Advanced Computer Science and Engineering' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],
        'Information Technology' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_it'],
        'Master of Computer Applications' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_ca'],
        'BCA' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_ca'],
        'Computer Application' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_ca'],
        'Artificial Intelligence and Data Science' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],
        'Artificial Intelligence & Machine Learning' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],
        'Cyber Security' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],
        'Data Science' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],

        // Core Engineering
        'Mechanical Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Mechanical' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Chemical Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_che'],
        'Chemical Enginneering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_che'],
        'Civil Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_civ'],
        'civil Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_civ'],
        'Textile Technology' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_tt'],
        'TEXTILE TECHNOLOGY' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_tt'],

        // Electrical, Electronics & Communication
        'Electronics and Communication Engineering' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_ece'],
        'Digital Electronics and Communication System' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_ece'],
        'Communication and Signal Processing' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_ece'],
        'Embedded Systems' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_ece'],
        'Very Large Scale Integration (VLSI)' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_ece'],
        'Electrical and Electronics Engineering' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_eee'],
        'Power Electronics and Drives' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_eee'],
        'Power Systems' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_eee'],
        'Electrical Vehicle Technology' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_eee'],
        'Autonomous Electric Vehicles' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_eee'],
        'Biomedical Engineering' => ['code' => 'sc_eeceng', 'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING', 'db_code' => 'dep_bme'],

        // Biotechnology & Pharmaceutical
        'Biotechnology' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_bio'],
        'Biotechnology and Bioprocess Engineering' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_bio'],
        'Bioinformatics' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_bioinfo'],
        'Pharmaceutical Sciences' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_ps'],
        'BPharmacy' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_ps'],
        'MASTER OF PHARMACY' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_ps'],
        'DOCTOR OF PHARMACY' => ['code' => 'sc_bps', 'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES', 'db_code' => 'dep_ps'],

        // Core Engineering Specializations
        'Energy Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Machine Design' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'cad/cam/cae' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Thermal Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Smart Manufacuring' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_mech'],
        'Sustainable Smart Construction' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_civ'],
        'Petroleum Engineering' => ['code' => 'sc_ceng', 'name' => 'SCHOOL OF CORE ENGINEERING', 'db_code' => 'dep_che'],

        // Computing Specializations
        'Computer networks & information security' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_cse'],
        'Data Communication & Networking' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_it'],
        'Image Processing & Machine Vision' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_cse'],
        'IOT' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_cse'],
        'machine learning' => ['code' => 'sc_ci', 'name' => 'SCHOOL OF COMPUTING AND INFORMATICS', 'db_code' => 'dep_acse'],

        // Agriculture Specializations
        'Farm Machinery' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_agri'],
        'Food Processing Technology' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_foodtech'],

        // Management Specializations
        'Human Resourse' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'Marketing' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'Finance' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],

        // Law & Management
        'Master of Business Administration' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'Management' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'BACHELOR OF BUSINESS MANAGEMENT' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'BACHELOR OF BUSINESS ADMINISTRATION' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_mba'],
        'B.A (LLB)' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_law'],
        'B.B.A (LLB)' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_law'],
        'LAW' => ['code' => 'sc_lm', 'name' => 'SCHOOL OF LAW & MANAGEMENT', 'db_code' => 'dep_law'],

        // Agriculture & Food Technology
        'Agricultural Engineering' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_agri'],
        'Agriculture' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_agri'],
        'Food Technology' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_foodtech'],
        'Food Processing Technology' => ['code' => 'sc_aft', 'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY', 'db_code' => 'dep_foodtech'],

        // Applied Sciences & Humanities
        'Physics' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_phy'],
        'physics' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_phy'],
        'Chemistry' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_chem'],
        'chemistry' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_chem'],
        'Mathematics' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_maths'],
        'English' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_eng'],
        'ENGLISH' => ['code' => 'sc_ash', 'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES', 'db_code' => 'dep_eng'],

        // Diploma
        'Diploma' => ['code' => 'dip', 'name' => 'DIPLOMA', 'db_code' => 'dep_dip'],

        // Education
        'Department of Education' => ['code' => 'sc_edu', 'name' => 'SCHOOL OF EDUCATION', 'db_code' => 'dep_education'],
    ];

    /**
     * Load dynamic mappings from storage file or memory cache.
     */
    public static function getDynamicMappings(): array
    {
        if (self::$inMemoryDynamicMappings !== null) {
            return self::$inMemoryDynamicMappings;
        }

        $path = self::getStoragePath();
        if (file_exists($path)) {
            $content = json_decode(@file_get_contents($path), true);
            if (is_array($content)) {
                self::$inMemoryDynamicMappings = $content;
                return self::$inMemoryDynamicMappings;
            }
        }

        self::$inMemoryDynamicMappings = [
            'courses' => [],
            'departments' => []
        ];

        return self::$inMemoryDynamicMappings;
    }

    /**
     * Save dynamic mappings to storage file and refresh cache.
     */
    public static function saveDynamicMappings(array $data): bool
    {
        self::$inMemoryDynamicMappings = $data;

        $path = self::getStoragePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $success = @file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;

        try {
            if (class_exists(Cache::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) {
                Cache::forget('lms_dynamic_register_mappings');
            }
        } catch (\Throwable $e) {
            // Non-fatal if application is not booted
        }

        return $success;
    }

    /**
     * Get all Course Mappings (Static + Dynamic merged).
     */
    public static function getCourseMapping(): array
    {
        $dynamic = self::getDynamicMappings();
        $merged = self::$courseMapping;
        if (!empty($dynamic['courses']) && is_array($dynamic['courses'])) {
            foreach ($dynamic['courses'] as $code => $name) {
                $merged[strtoupper($code)] = $name;
            }
        }
        return $merged;
    }

    /**
     * Get all Department Mappings (Static + Dynamic merged).
     */
    public static function getDepartmentMapping(?string $courseCode = null): array
    {
        $dynamic = self::getDynamicMappings();
        $merged = self::$departmentMapping;

        if (!empty($dynamic['departments']) && is_array($dynamic['departments'])) {
            foreach ($dynamic['departments'] as $cCode => $depts) {
                $cCode = strtoupper($cCode);
                if (!isset($merged[$cCode])) {
                    $merged[$cCode] = [];
                }
                foreach ($depts as $dCode => $deptInfo) {
                    $dName = is_array($deptInfo) ? ($deptInfo['department_name'] ?? '') : (string)$deptInfo;
                    $merged[$cCode][$dCode] = $dName;
                }
            }
        }

        if ($courseCode !== null) {
            $courseCode = strtoupper($courseCode);
            return $merged[$courseCode] ?? [];
        }

        return $merged;
    }

    /**
     * Register or update a Dynamic Mapping.
     */
    public static function registerDynamicMapping(
        string $courseCode,
        string $courseName,
        string $deptCode,
        string $deptName,
        ?string $schoolCode = null,
        ?string $schoolName = null,
        ?string $dbDeptCode = null
    ): bool {
        $courseCode = strtoupper(trim($courseCode));
        $deptCode = str_pad(trim($deptCode), 2, '0', STR_PAD_LEFT);
        $courseName = trim($courseName);
        $deptName = trim($deptName);

        // Attempt to auto-resolve school if not explicitly provided
        if (empty($schoolCode)) {
            $resolved = self::resolveSchoolForDepartment($deptName, $courseCode);
            if ($resolved) {
                $schoolCode = $resolved['code'];
                $schoolName = $resolved['name'];
                $dbDeptCode = $dbDeptCode ?: $resolved['db_code'];
            }
        } elseif (empty($schoolName)) {
            $sch = School::where('code', $schoolCode)->first();
            if ($sch) {
                $schoolName = $sch->name;
            }
        }

        $dynamic = self::getDynamicMappings();
        
        // Save course
        if (!empty($courseName)) {
            $dynamic['courses'][$courseCode] = $courseName;
        }

        // Save department mapping
        if (!isset($dynamic['departments'][$courseCode])) {
            $dynamic['departments'][$courseCode] = [];
        }

        $dynamic['departments'][$courseCode][$deptCode] = [
            'department_name' => $deptName,
            'school_code'     => $schoolCode,
            'school_name'     => $schoolName,
            'db_dept_code'    => $dbDeptCode,
            'created_at'      => now()->toIso8601String(),
        ];

        return self::saveDynamicMappings($dynamic);
    }

    /**
     * Remove a Dynamic Mapping.
     */
    public static function removeDynamicMapping(string $courseCode, string $deptCode): bool
    {
        $courseCode = strtoupper(trim($courseCode));
        $deptCode = str_pad(trim($deptCode), 2, '0', STR_PAD_LEFT);

        $dynamic = self::getDynamicMappings();
        if (isset($dynamic['departments'][$courseCode][$deptCode])) {
            unset($dynamic['departments'][$courseCode][$deptCode]);
            return self::saveDynamicMappings($dynamic);
        }
        return false;
    }

    /**
     * Retrieve all Dynamic Mappings as a flat list.
     */
    public static function getDynamicMappingsList(): array
    {
        $dynamic = self::getDynamicMappings();
        $list = [];

        if (!empty($dynamic['departments'])) {
            foreach ($dynamic['departments'] as $cCode => $depts) {
                $cName = $dynamic['courses'][$cCode] ?? (self::$courseMapping[$cCode] ?? $cCode);
                foreach ($depts as $dCode => $meta) {
                    $list[] = [
                        'course_code'     => $cCode,
                        'course_name'     => $cName,
                        'department_code' => $dCode,
                        'department_name' => is_array($meta) ? ($meta['department_name'] ?? '') : $meta,
                        'school_code'     => is_array($meta) ? ($meta['school_code'] ?? null) : null,
                        'school_name'     => is_array($meta) ? ($meta['school_name'] ?? null) : null,
                        'db_dept_code'    => is_array($meta) ? ($meta['db_dept_code'] ?? null) : null,
                        'created_at'      => is_array($meta) ? ($meta['created_at'] ?? null) : null,
                        'is_dynamic'      => true,
                    ];
                }
            }
        }

        return $list;
    }

    /**
     * Get all active mappings (Static + Dynamic) combined with school details.
     */
    public static function getAllMappingsWithSchools(): array
    {
        $courses = self::getCourseMapping();
        $deptsByCourse = self::getDepartmentMapping();
        $dynamic = self::getDynamicMappings();
        $all = [];

        foreach ($deptsByCourse as $cCode => $depts) {
            $cName = $courses[$cCode] ?? $cCode;
            foreach ($depts as $dCode => $dName) {
                $isDynamic = isset($dynamic['departments'][$cCode][$dCode]);
                $schoolInfo = null;
                $dbCode = null;

                if ($isDynamic && is_array($dynamic['departments'][$cCode][$dCode])) {
                    $meta = $dynamic['departments'][$cCode][$dCode];
                    $schoolInfo = [
                        'code' => $meta['school_code'] ?? null,
                        'name' => $meta['school_name'] ?? null,
                        'db_code' => $meta['db_dept_code'] ?? null,
                    ];
                    $dbCode = $meta['db_dept_code'] ?? null;
                }

                if (!$schoolInfo || empty($schoolInfo['code'])) {
                    $schoolInfo = self::resolveSchoolForDepartment($dName, $cCode);
                }

                $all[] = [
                    'course_code'     => $cCode,
                    'course_name'     => $cName,
                    'department_code' => $dCode,
                    'department_name' => $dName,
                    'school_code'     => $schoolInfo['code'] ?? 'sc_unknown',
                    'school_name'     => $schoolInfo['name'] ?? 'General Academic Division',
                    'db_dept_code'    => $dbCode ?: ($schoolInfo['db_code'] ?? null),
                    'is_dynamic'      => $isDynamic,
                ];
            }
        }

        return $all;
    }

    /**
     * Helper to resolve the governing School and database department code.
     */
    public static function resolveSchoolForDepartment(string $departmentName, ?string $courseCode = null): ?array
    {
        $cleanName = trim($departmentName);

        // 1. Check known lookup dictionary
        if (isset(self::$knownDepartmentSchools[$cleanName])) {
            return self::$knownDepartmentSchools[$cleanName];
        }

        // Case-insensitive / normalized lookup in known dictionary
        foreach (self::$knownDepartmentSchools as $key => $val) {
            if (strcasecmp($key, $cleanName) === 0 || stripos($cleanName, $key) !== false || stripos($key, $cleanName) !== false) {
                return $val;
            }
        }

        // 2. Query database Department model if available
        try {
            $dept = Department::with('school')
                ->where('name', 'like', "%{$cleanName}%")
                ->orWhere('code', 'like', "%{$cleanName}%")
                ->first();

            if ($dept && $dept->school) {
                return [
                    'code'    => $dept->school->code,
                    'name'    => $dept->school->name,
                    'db_code' => $dept->code,
                ];
            }
        } catch (\Throwable $e) {
            // Fallback gracefully if database is momentarily unreachable
        }

        return null;
    }

    /**
     * Parses a student registration number.
     * Extracts Course, Department, Governing School, Roll Number, and Admission Year.
     */
    public static function parse($registerNumber)
    {
        $registerNumber = strtoupper(trim((string)$registerNumber));
        
        // Register number MUST be exactly 10 characters
        if (strlen($registerNumber) !== 10) {
            return null;
        }

        if (!preg_match('/^\d{2}[A-Z0-9]{2}[A-Z0-9]{1,2}\d+$/', $registerNumber)) {
            return null;
        }

        $allCourses = self::getCourseMapping();
        $allDepartments = self::getDepartmentMapping();
        $dynamicMappings = self::getDynamicMappings();

        $courseCode = null;
        $deptCode = null;
        
        // P1 and P2 are 2 chars long, others are 1 char long
        $c2 = substr($registerNumber, 4, 2);
        if (isset($allCourses[$c2])) {
            $courseCode = $c2;
            $deptCode = substr($registerNumber, 6, 2);
        } else {
            $c1 = substr($registerNumber, 4, 1);
            if (isset($allCourses[$c1])) {
                $courseCode = $c1;
                $deptCode = substr($registerNumber, 5, 2);
            }
        }
        
        if (!$courseCode || !$deptCode) {
            return null;
        }
        
        if (!isset($allCourses[$courseCode])) {
            return null; // Invalid course code
        }
        
        $courseName = $allCourses[$courseCode];
        $deptName = $allDepartments[$courseCode][$deptCode] ?? null;

        if (!$deptName) {
            return null; // Invalid department code for this course
        }

        $isDynamic = isset($dynamicMappings['departments'][$courseCode][$deptCode]);
        $schoolCode = null;
        $schoolName = null;
        $dbDeptCode = null;

        if ($isDynamic && is_array($dynamicMappings['departments'][$courseCode][$deptCode])) {
            $meta = $dynamicMappings['departments'][$courseCode][$deptCode];
            $schoolCode = $meta['school_code'] ?? null;
            $schoolName = $meta['school_name'] ?? null;
            $dbDeptCode = $meta['db_dept_code'] ?? null;
        }

        if (!$schoolCode) {
            $resolvedSchool = self::resolveSchoolForDepartment($deptName, $courseCode);
            if ($resolvedSchool) {
                $schoolCode = $resolvedSchool['code'];
                $schoolName = $resolvedSchool['name'];
                $dbDeptCode = $dbDeptCode ?: ($resolvedSchool['db_code'] ?? null);
            }
        }

        $yearPrefix = substr($registerNumber, 0, 2);
        $admissionYear = '20' . $yearPrefix;
        $campusCode = substr($registerNumber, 2, 2);
        $rollNumber = substr($registerNumber, strlen($courseCode) === 2 ? 8 : 7);

        return [
            'valid'              => true,
            'register_number'    => $registerNumber,
            'course_code'        => $courseCode,
            'course_name'        => $courseName,
            'department_code'    => $deptCode,
            'department_name'    => $deptName,
            'school_code'        => $schoolCode,
            'school_name'        => $schoolName,
            'db_department_code' => $dbDeptCode,
            'admission_year'     => $admissionYear,
            'campus_code'        => $campusCode,
            'roll_number'        => $rollNumber,
            'is_dynamic'         => $isDynamic,
        ];
    }
}
