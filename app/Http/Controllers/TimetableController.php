<?php

namespace App\Http\Controllers;

use App\Data;
use App\Models;
use Carbon\CarbonImmutable;
use Hybridly\Contracts\HybridResponse;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function view(Models\Employee|null $employee = null): HybridResponse
    {
        $now = CarbonImmutable::now();

        return hybridly('timetable', [
            'current_employee' => $employee ? Data\EmployeeData::from($employee) : null,
            'employees' => Data\EmployeeData::collect(
                Models\Employee::where('current', true)->get(),
            ),
            'lessons' => $employee ? Data\LessonData::collect(
                $employee->lessons()
                    ->where('start_at', '>', $now->startOfWeek())
                    ->where('start_at', '<', $now->endOfWeek())
                    ->with(['wondeClass' => ['students', 'employees']])
                    ->orderBy('start_at')
                    ->get(),
            ) : [],
        ]);
    }
}
