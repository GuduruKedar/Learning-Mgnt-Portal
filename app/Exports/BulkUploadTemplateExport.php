<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BulkUploadTemplateExport implements WithMultipleSheets, Export
{
    protected $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function sheets(): array
    {
        $sheets = [];
        $user = \Illuminate\Support\Facades\Auth::user();
        $isCoordinator = $user && $user->role === 'admin';
        
        $coordSchoolName = null;
        $coordDeptName = null;
        
        if ($isCoordinator && $user->profile) {
            $school = \App\Models\School::where('code', $user->profile->schools_id)->first();
            if ($school) $coordSchoolName = $school->name;
            
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) $coordDeptName = $dept->name;
        }

        if ($this->role === 'stu') {
            $sheets[] = new StudentTemplateSheet($coordSchoolName, $coordDeptName);
        } elseif ($this->role === 'sta' || $this->role === 'admin') {
            $sheets[] = new StaffTemplateSheet($coordSchoolName, $coordDeptName);
        } else {
            $sheets[] = new StaffTemplateSheet($coordSchoolName, $coordDeptName);
            $sheets[] = new StudentTemplateSheet($coordSchoolName, $coordDeptName);
        }

        $sheets[] = new DepartmentsSheet();
        $sheets[] = new SchoolsSheet();
        $sheets[] = new LevelsSheet();

        return $sheets;
    }
}
