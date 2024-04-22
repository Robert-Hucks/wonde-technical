<?php

namespace App\Console\Commands;

use App\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wonde;

class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data to fill tables from Wonde API';

    /**
     * Execute the console command.
     */
    public function handle(Wonde\Client $wonde)
    {
        $school = $wonde->school(config('services.wonde.school_id'));

        $this->info('Fetching employees');

        $employees = [];
        foreach ($school->employees->all(['employment_details']) as $employee) {
            $employees[] = [
                'id' => $employee->id,
                'title' => $employee->title,
                'forename' => $employee->forename,
                'surname' => $employee->surname,
                'current' => $employee->employment_details->data->current,
            ];
        }
        Models\Employee::upsert($employees, 'id');

        $this->info('Fetching students');

        $students = [];
        foreach ($school->students->all() as $student) {
            $students[] = [
                'id' => $student->id,
                'forename' => $student->forename,
                'surname' => $student->surname,
            ];
        }
        Models\Student::upsert($students, 'id');

        $this->info('Fetching class data');

        $classes = [];
        $lessons = [];
        $classStudents = [];
        $classEmployees = [];
        foreach ($school->classes->all(['employees', 'students', 'lessons']) as $class) {
            $classes[] = [
                'id' => $class->id,
                'name' => $class->name,
                'code' => $class->code,
            ];

            array_push(
                $lessons,
                ...array_map(
                    fn (object $lesson) => [
                        'id' => $lesson->id,
                        'class_id' => $class->id,
                        'employee_id' => $lesson->employee,
                        'room' => $lesson->room,
                        'start_at' => $lesson->start_at->date,
                        'end_at' => $lesson->end_at->date,
                    ],
                    array_filter(
                        $class->lessons->data,
                        fn (object $lesson) => !is_null($lesson->employee)
                    ),
                ),
            );

            $classStudents[$class->id] = array_map(
                fn (object $student) => $student->id,
                $class->students->data,
            );

            $classEmployees[$class->id] = array_map(
                fn (object $employee) => (object) [
                    'id' => $employee->id,
                    'is_primary' => $employee->meta->is_main_teacher,
                ],
                $class->employees->data,
            );
        }
        Models\WondeClass::upsert($classes, 'id');
        Models\Lesson::upsert($lessons, 'id');
        DB::table('class_students')->upsert(
            array_merge(
                ...array_map(
                    function (string $class_id, array $students) {
                        return array_map(
                            fn (string $student_id) => [
                                'class_id' => $class_id,
                                'student_id' => $student_id,
                            ],
                            $students,
                        );
                    },
                    array_keys($classStudents),
                    array_values($classStudents),
                )
            ),
            ['class_id', 'student_id'],
        );
        DB::table('class_employees')->upsert(
            array_merge(
                ...array_map(
                    function (string $class_id, array $employees) {
                        return array_map(
                            fn (object $employee) => [
                                'class_id' => $class_id,
                                'employee_id' => $employee->id,
                                'is_primary' => $employee->is_primary,
                            ],
                            $employees,
                        );
                    },
                    array_keys($classEmployees),
                    array_values($classEmployees),
                )
            ),
            ['class_id', 'employee_id'],
            ['is_primary']
        );
    }
}
