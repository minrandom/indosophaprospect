<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Department;
use Illuminate\Http\Request;

class DeptDashboardController extends Controller
{
    //

    public function index(Request $request, $hospitalId, $departmentId)
    {
        // ===== Core objects =====
        $hospital = Hospital::findOrFail($hospitalId);
        $department = Department::findOrFail($departmentId);

        // for the dropdown (all departments list)
        $departments = Department::orderBy('name')->get();

        // ===== TAB 1: Human Resource (dummy) =====
        $hr = [
            'people' => [
                [
                    'avatar' => 'https://via.placeholder.com/48',
                    'name' => 'Dr. Ahmad Rizky',
                    'role' => 'Doctor',
                    'phone' => '+62 8120001',
                    'influence' => 'Financial',
                    'department' => $department->name,
                ],
                [
                    'avatar' => 'https://via.placeholder.com/48',
                    'name' => 'Nurse Sinta',
                    'role' => 'Nurse',
                    'phone' => '+62 8120002',
                    'influence' => 'User',
                    'department' => $department->name,
                ],
                [
                    'avatar' => 'https://via.placeholder.com/48',
                    'name' => 'Dr. Budi',
                    'role' => 'Director',
                    'phone' => '+62 8120003',
                    'influence' => 'Direksi',
                    'department' => $department->name,
                ],
                [
                    'avatar' => 'https://via.placeholder.com/48',
                    'name' => 'Dr. Maya',
                    'role' => 'Doctor',
                    'phone' => '+62 8120004',
                    'influence' => 'Purchasing',
                    'department' => $department->name,
                ],
                [
                    'avatar' => 'https://via.placeholder.com/48',
                    'name' => 'Nurse Rina',
                    'role' => 'Nurse',
                    'phone' => '+62 8120005',
                    'influence' => 'User',
                    'department' => $department->name,
                ],
            ],
        ];

        // ===== TAB 2: Market Information (dummy) =====
        $market = [
            'ib_total' => 12,
            'ib_brands' => [
                ['brand' => 'MAQUET', 'count' => 3],
                ['brand' => 'GETINGE', 'count' => 4],
                ['brand' => '...', 'count' => 5],
            ],
            'market_total' => 22,
            'market_brands' => [
                ['brand' => 'DRAGER', 'count' => 7],
                ['brand' => 'PHILIPS', 'count' => 2],
                ['brand' => 'GE', 'count' => 8],
                ['brand' => '...', 'count' => 5],
            ],
        ];

        // ===== TAB 3: IB Summary (dummy) =====
        $ib = [
            'covered_total' => 23,
            'covered_pie' => [55.6, 44.4], // covered, not covered
            'warranties' => [300, 600, 1500], // Active, Ending Soon, Finished
            'contracts' => [200, 400, 1200],  // Active, Ending Soon, Finished

            'equipments' => [
                [
                    'equipment' => 'VENTILATOR',
                    'model' => 'Servo-i',
                    'status' => 'Good',
                    'coverage' => 'Covered',
                    'coverage_type' => 'Warranty',
                ],
                [
                    'equipment' => 'ANESTHESIA',
                    'model' => 'Flow-i',
                    'status' => 'Good',
                    'coverage' => 'Covered',
                    'coverage_type' => 'Service Contract',
                ],
                [
                    'equipment' => 'MONITOR',
                    'model' => 'X3',
                    'status' => 'Warning',
                    'coverage' => 'Not Covered',
                    'coverage_type' => '-',
                ],
                [
                    'equipment' => 'PUMP',
                    'model' => 'P500',
                    'status' => 'Good',
                    'coverage' => 'Covered',
                    'coverage_type' => 'Warranty',
                ],
                [
                    'equipment' => 'ECG',
                    'model' => 'E100',
                    'status' => 'Repair',
                    'coverage' => 'Not Covered',
                    'coverage_type' => '-',
                ],
            ],
        ];

        return view('admin.department_overview', compact(
            'hospital',
            'department',
            'departments',
            'hr',
            'market',
            'ib'
        ));
    }

}
