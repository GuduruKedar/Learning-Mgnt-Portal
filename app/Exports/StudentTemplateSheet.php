<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentTemplateSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
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
    }

    public function array(): array
    {
        $school1 = $this->coordSchoolName ?? 'School of Computing & Informatics';
        $dept1 = $this->coordDeptName ?? 'Information Technology';
        $level1 = 'UG';
        $program1 = 'B.Tech in Information Technology';

        $school2 = $this->coordSchoolName ?? 'School of Core Engineering';
        $dept2 = $this->coordDeptName ?? 'Mechanical Engineering';
        $level2 = 'UG';
        $program2 = 'B.Tech in Mechanical Engineering';

        if ($this->coordDeptName) {
            $school2 = $this->coordSchoolName;
            $dept2 = $this->coordDeptName;
            
            $dept = \App\Models\Department::where('name', $this->coordDeptName)->first();
            if ($dept) {
                $programs = \App\Models\Program::where('department_id', $dept->id)->take(2)->get();
                if ($programs->count() > 0) {
                    $level1 = $programs[0]->level;
                    $program1 = $programs[0]->name;
                    if ($programs->count() > 1) {
                        $level2 = $programs[1]->level;
                        $program2 = $programs[1]->name;
                    } else {
                        $level2 = $level1;
                        $program2 = $program1;
                    }
                }
            }
        }

        return [
            [
                $school1,
                $dept1,
                $level1,
                $program1,
                '241FA07001',
                'John',
                '',
                'Doe',
                '',
                'john.doe@vignan.ac.in',
                '9876543210',
                'Student#963'
            ],
            [
                $school2,
                $dept2,
                $level2,
                $program2,
                '241FA08002',
                'Jane',
                'A',
                'Smith',
                '',
                'jane.smith@vignan.ac.in',
                '9876543211',
                'Student#963'
            ]
        ];
    }

    public function title(): string
    {
        return 'Student Template Data';
    }
}
