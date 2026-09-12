<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolAndDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = [

            /*
            |--------------------------------------------------------------------------
            | 01. SCHOOL OF CORE ENGINEERING
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_ceng',
                'name' => 'SCHOOL OF CORE ENGINEERING',

                'departments' => [

                    [
                        'code' => 'dep_mech',
                        'name' => 'Mechanical Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_me',
                                'name' => 'B.Tech in Mechanical Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_ra',
                                'name' => 'B.Tech in Robotics and Automation',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_sm',
                                'name' => 'M.Tech in Smart Manufacturing',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_me',
                                'name' => 'Ph.D. in Mechanical Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_che',
                        'name' => 'Chemical Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_chem',
                                'name' => 'B.Tech In Chemical Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'phd_chem_eng',
                                'name' => 'Ph.D. in Chemical Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_civ',
                        'name' => 'Civil Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_civil',
                                'name' => 'B.Tech In Civil Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_ssc',
                                'name' => 'M.Tech In Sustainable Smart Construction',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_civil',
                                'name' => 'Ph.D. in Civil Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_tt',
                        'name' => 'Textile Technology',

                        'programs' => [
                            [
                                'code' => 'btech_tt',
                                'name' => 'B.Tech in Textile Technology',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_technical_textiles',
                                'name' => 'B.Tech In Technical Textiles',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'phd_tt',
                                'name' => 'Ph.D. in Textile Technology',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 02. SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_eeceng',
                'name' => 'SCHOOL OF ELECTRICAL ELECTRONICS AND COMMUNICATION ENGINEERING',

                'departments' => [

                    [
                        'code' => 'dep_ece',
                        'name' => 'Electronics and Communication Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_ece',
                                'name' => 'B.Tech in Electronics and Communication Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_vlsi',
                                'name' => 'B.Tech in Electronics Engineering (VLSI Design and Technology)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_iot',
                                'name' => 'M.Tech in Internet of Things',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'mtech_vlsi',
                                'name' => 'M.Tech in VLSI',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_ece',
                                'name' => 'Ph.D. in Electronics and Communication Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_bme',
                        'name' => 'Biomedical Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_bme',
                                'name' => 'B.Tech in Biomedical Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'phd_bme',
                                'name' => 'Ph.D. in Biomedical Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_eee',
                        'name' => 'Electrical and Electronics Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_eee',
                                'name' => 'B.Tech in Electrical and Electronics Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_aev',
                                'name' => 'M.Tech in Autonomous Electric Vehicles',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_eee',
                                'name' => 'Ph.D. in Electrical and Electronics Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 03. SCHOOL OF COMPUTING AND INFORMATICS
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_ci',
                'name' => 'SCHOOL OF COMPUTING AND INFORMATICS',

                'departments' => [

                    [
                        'code' => 'dep_cse',
                        'name' => 'Computer Science and Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_cse',
                                'name' => 'B.Tech in Computer Science & Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_cse',
                                'name' => 'M.Tech in Computer Science & Engineering',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_cse',
                                'name' => 'Ph.D. in Computer Science and Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_acse',
                        'name' => 'Advanced Computer Science and Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_aiml',
                                'name' => 'B.Tech in Computer Science and Engineering (Artificial Intelligence and Machine Learning)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_cyber',
                                'name' => 'B.Tech in Computer Science and Engineering (Cyber Security)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_csbs',
                                'name' => 'B.Tech in Computer Science and Business System',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_ds',
                                'name' => 'B.Tech in Computer Science and Engineering (Data Science)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'btech_iot',
                                'name' => 'B.Tech in Computer Science and Engineering (IOT)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'bsc_ds',
                                'name' => 'B.Sc. (Data Science)',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'msc_ds',
                                'name' => 'M.Sc (Data Science)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'mtech_aids',
                                'name' => 'M.Tech in Artificial Intelligence and Data Science',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'mtech_research',
                                'name' => 'M.Tech by Research',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_acse',
                                'name' => 'Ph.D in Advanced Computer Science and Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_aiml',
                                'name' => 'Ph.D in Artificial Intelligence & Machine Learning',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_cyber',
                                'name' => 'Ph.D in Cyber Security',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_ds',
                                'name' => 'Ph.D in Data Science',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_it',
                        'name' => 'Information Technology',

                        'programs' => [
                            [
                                'code' => 'btech_it',
                                'name' => 'B.Tech in Information Technology',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_ca',
                        'name' => 'Computer Applications',

                        'programs' => [
                            [
                                'code' => 'bca_ca',
                                'name' => 'Bachelor of Computer Applications (BCA)',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                             [
                                'code' => 'bca_cah',
                                'name' => 'Bachelor of Computer Applications (BCA(Hons))',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mca',
                                'name' => 'Master Of Computer Applications (MCA)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_ca',
                                'name' => 'Ph.D. in Computer Applications',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 04. SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_bps',
                'name' => 'SCHOOL OF BIOTECHNOLOGY & PHARMACEUTICAL SCIENCES',

                'departments' => [

                    [
                        'code' => 'dep_bio',
                        'name' => 'Biotechnology',

                        'programs' => [
                            [
                                'code' => 'btech_bt',
                                'name' => 'B.Tech in Biotechnology',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_bt',
                                'name' => 'M.Tech in Biotechnology',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_bt',
                                'name' => 'Ph.D. in Biotechnology',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_ps',
                        'name' => 'Pharmaceutical Sciences',

                        'programs' => [
                            [
                                'code' => 'bpharm',
                                'name' => 'Bachelor of Pharmacy (B.Pharm)',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mpharm',
                                'name' => 'M.Pharm (Pharmaceutics)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'pharmd',
                                'name' => 'Pharm.D.',
                                'level' => 'PG',
                                'duration_years' => 6,
                            ],
                            [
                                'code' => 'phd_pharma',
                                'name' => 'Pharmaceutical Sciences',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_bioinfo',
                        'name' => 'Bioinformatics',

                        'programs' => [
                            [
                                'code' => 'btech_bioinfo',
                                'name' => 'B.Tech in Bioinformatics',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 05. SCHOOL OF LAW & MANAGEMENT
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_lm',
                'name' => 'SCHOOL OF LAW & MANAGEMENT',

                'departments' => [

                    [
                        'code' => 'dep_mba',
                        'name' => 'Department of Management Studies',

                        'programs' => [
                            [
                                'code' => 'bba',
                                'name' => 'BBA (Bachelor of Business Administration)',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'mba',
                                'name' => 'MBA (Business Management)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_mgt',
                                'name' => 'Ph.D. in Management Studies',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_law',
                        'name' => 'Institute of Law',

                        'programs' => [
                            [
                                'code' => 'ba_llb',
                                'name' => 'BA LL.B (H)',
                                'level' => 'UG',
                                'duration_years' => 5,
                            ],
                            [
                                'code' => 'bba_llb',
                                'name' => 'BBA LL.B (H)',
                                'level' => 'UG',
                                'duration_years' => 5,
                            ],
                            [
                                'code' => 'phd_law',
                                'name' => 'Ph.D in LAW',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 06. SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_aft',
                'name' => 'SCHOOL OF AGRICULTURE & FOOD TECHNOLOGY',

                'departments' => [

                    [
                        'code' => 'dep_agri',
                        'name' => 'Agricultural Engineering',

                        'programs' => [
                            [
                                'code' => 'btech_agri',
                                'name' => 'B.Tech in Agricultural Engineering',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_fm',
                                'name' => 'M.Tech in Farm Machinery',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_agri',
                                'name' => 'Ph.D. in Agricultural Engineering',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_viat',
                        'name' => 'Vignan Institute of Agriculture and Technology',

                        'programs' => [
                            [
                                'code' => 'bsc_agri',
                                'name' => 'B.Sc. (Hons.) Agriculture',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_foodtech',
                        'name' => 'Food Technology',

                        'programs' => [
                            [
                                'code' => 'btech_food',
                                'name' => 'B.Tech in Food Technology',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'mtech_food',
                                'name' => 'M.Tech in Food Processing Technology',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_food',
                                'name' => 'Ph.D. in Food Technology',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 07. SCHOOL OF APPLIED SCIENCES & HUMANITIES
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_ash',
                'name' => 'SCHOOL OF APPLIED SCIENCES & HUMANITIES',

                'departments' => [

                    [
                        'code' => 'dep_phy',
                        'name' => 'Physics',

                        'programs' => [
                            [
                                'code' => 'phd_phy',
                                'name' => 'Ph.D. in Physics',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_chem',
                        'name' => 'Chemistry',

                        'programs' => [
                            [
                                'code' => 'msc_chem',
                                'name' => 'M.Sc (Chemistry)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'msc_org_chem',
                                'name' => 'M.Sc (Organic Chemistry)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_chemistry',
                                'name' => 'Ph.D. in Chemistry',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_maths',
                        'name' => 'Mathematics and Statistics',

                        'programs' => [
                            [
                                'code' => 'bsc_maths_stats_cs',
                                'name' => 'B.Sc. in Mathematics, Statistics and Computer Science',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'bsc_actuarial',
                                'name' => 'B.Sc. in Actuarial Science',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'dual_bsc_msc_ds',
                                'name' => 'Dual B.Sc.+M.Sc. in Data Science',
                                'level' => 'PG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_statistics',
                                'name' => 'Ph.D. in Statistics',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_maths',
                                'name' => 'Ph.D. in Mathematics',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_eng',
                        'name' => 'Department of English and Other Indian & Foreign Languages',

                        'programs' => [
                            [
                                'code' => 'ma_english',
                                'name' => 'M.A. English',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_english',
                                'name' => 'Ph.D. in English',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],

                    [
                        'code' => 'dep_ssh',
                        'name' => 'Social Sciences & Humanities',

                        'programs' => [
                            [
                                'code' => 'bsc_psychology',
                                'name' => 'B.Sc. in Psychology',
                                'level' => 'UG',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'msc_psychology_bsc',
                                'name' => 'MSc in Psychology (BSc. Psychology Background)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'msc_psychology_non_bsc',
                                'name' => 'MSc in Psychology (Non-BSc. Psychology Background)',
                                'level' => 'PG',
                                'duration_years' => 2,
                            ],
                            [
                                'code' => 'phd_psychology',
                                'name' => 'PhD in Psychology',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'phd_social_sciences',
                                'name' => 'Doctor of Philosophy',
                                'level' => 'PhD',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 08. DIPLOMA
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'dip',
                'name' => 'DIPLOMA',

                'departments' => [

                    [
                        'code' => 'dep_dip',
                        'name' => 'Diploma',

                        'programs' => [
                            [
                                'code' => 'dip_cse',
                                'name' => 'Diploma in Computer Science and Engineering',
                                'level' => 'Diploma',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'dip_ece',
                                'name' => 'Diploma in Electronics and Communications Engineering',
                                'level' => 'Diploma',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'dip_eee',
                                'name' => 'Diploma in Electrical and Electronics Engineering',
                                'level' => 'Diploma',
                                'duration_years' => 3,
                            ],
                            [
                                'code' => 'dip_mech',
                                'name' => 'Diploma in Mechanical Engineering',
                                'level' => 'Diploma',
                                'duration_years' => 3,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 09. SCHOOL OF EDUCATION
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_edu',
                'name' => 'SCHOOL OF EDUCATION',

                'departments' => [

                    [
                        'code' => 'dep_education',
                        'name' => 'Department of Education',

                        'programs' => [
                            [
                                'code' => 'ba_bed',
                                'name' => 'B.A. B.Ed.',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                            [
                                'code' => 'bsc_bed',
                                'name' => 'B.Sc. B.Ed.',
                                'level' => 'UG',
                                'duration_years' => 4,
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. CENTER FOR CIVIL SERVICES
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'sc_cs',
                'name' => 'CENTER FOR CIVIL SERVICES',

                'departments' => [

                    [
                        'code' => 'dep_cs',
                        'name' => 'Department of Civil Services',

                        'programs' => [
                            [
                                'code' => 'prog_cs',
                                'name' => 'Civil Services Coaching & Training',
                                'level' => 'UG',
                                'duration_years' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create Schools → Departments → Programs
        |--------------------------------------------------------------------------
        */

        foreach ($schools as $schoolData) {

            $school = School::updateOrCreate(
                ['code' => $schoolData['code']],
                ['name' => $schoolData['name']]
            );

            foreach ($schoolData['departments'] as $deptData) {

                $department = Department::updateOrCreate(
                    ['code' => $deptData['code']],
                    [
                        'school_id' => $school->code,
                        'name' => $deptData['name'],
                    ]
                );

                foreach ($deptData['programs'] as $programData) {

                    Program::updateOrCreate(
                        ['code' => $programData['code']],
                        [
                            'department_id' => $department->id,
                            'name' => $programData['name'],
                            'level' => $programData['level'],
                            'duration_years' => $programData['duration_years'],
                        ]
                    );
                }
            }
        }
    }
}
