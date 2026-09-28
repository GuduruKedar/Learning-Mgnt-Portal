<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentTemplateSheet implements FromArray, WithHeadings, WithTitle
{
    protected $coordSchoolName;
    protected $coordDeptName;

    public function __construct($coordSchoolName = null, $coordDeptName = null)
    {
        $this->coordSchoolName = $coordSchoolName;
        $this->coordDeptName = $coordDeptName;
    }

    public function headings(): array
    {
        return [
            'school',
            'department',
            'level',
            'register_number',
            'firstname',
            'middlename',
            'lastname',
            'photo',
            'email',
            'mobile_number',
            'password'
        ];
    }

    public function array(): array
    {
        $school1 = $this->coordSchoolName ?? 'School of Computing';
        $dept1 = $this->coordDeptName ?? 'Information Technology';
        
        $school2 = $this->coordSchoolName ?? 'School of Core Engineering';
        $dept2 = $this->coordDeptName ?? 'Mechanical';
        
        $level1 = 'B.Tech'; // Maps to UG
        $level2 = 'Diploma'; // Maps to Diploma

        if ($this->coordDeptName) {
            $school2 = $this->coordSchoolName;
            $dept2 = $this->coordDeptName;
            
            $dept = \App\Models\Department::where('name', $this->coordDeptName)->first();
            if ($dept) {
                $programs = \App\Models\Program::where('department_id', $dept->id)->take(2)->get();
                if ($programs->count() > 0) {
                    // Try to show appropriate levels based on the department's actual programs
                    $level1 = $programs[0]->level === 'UG' ? 'B.Tech' : $programs[0]->level;
                    if ($programs->count() > 1) {
                        $level2 = $programs[1]->level === 'UG' ? 'B.Tech' : $programs[1]->level;
                    } else {
                        $level2 = $level1;
                    }
                }
            }
        }

        return [
            [
                $school1,
                $dept1,
                $level1,
                '241FA07001',
                'John',
                '',
                'Doe',
                '',
                'john.doe@vignan.ac.in',
                '9876543210',
                ''
            ],
            [
                $school2,
                $dept2,
                $level2,
                '241FE08002',
                'Jane',
                'A',
                'Smith',
                '',
                'jane.smith@vignan.ac.in',
                '9876543211',
                'MySecretPass123'
            ]
        ];
    }

    public function title(): string
    {
        return 'Student Template Data';
    }
}
