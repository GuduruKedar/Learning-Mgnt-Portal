<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffTemplateSheet implements FromArray, WithHeadings, WithTitle
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

    public function array(): array
    {
        $school1 = $this->coordSchoolName ?? 'School of Computing';
        $dept1 = $this->coordDeptName ?? 'Information Technology';
        
        $school2 = $this->coordSchoolName ?? 'School of Core Engineering';
        $dept2 = $this->coordDeptName ?? 'Mechanical';

        return [
            [
                $school1,
                $dept1,
                'EMP001',
                'John',
                '',
                'Doe',
                '',
                'john.doe@vignan.ac.in',
                '1234567890',
                ''
            ],
            [
                $school2,
                $dept2,
                'EMP002',
                'Jane',
                'A',
                'Smith',
                '',
                'jane.smith@vignan.ac.in',
                '0987654321',
                'MySecretPass123'
            ]
        ];
    }

    public function title(): string
    {
        return 'Staff Template Data';
    }
}
