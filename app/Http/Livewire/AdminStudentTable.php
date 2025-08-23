<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AdminStudentTable extends DataTableComponent
{
    protected $model = Student::class;
    public $school_id;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        $user = Auth::user();

        return Student::query()
            ->where('school_id', $this->school_id);
    }

    public function columns(): array
    {
        return [
            Column::make('NO.', 'id')
                ->sortable(),
            Column::make("Student's Name", 'name')
                ->sortable()
                ->searchable(),
            Column::make("Average Percentage", 'id')
                ->format(function ($value, $row) {
                    $attendancesAttend = Attendance::where('student_id', $row->id)
                        ->where('status', 'attend')
                        ->count();

                    $attendancesAbsent = Attendance::where('student_id', $row->id)
                        ->where('status', 'absent')
                        ->count();

                    $totalAttendances = $attendancesAttend + $attendancesAbsent;
                    if ($totalAttendances > 0) {
                        $attendancePercentage = ($attendancesAttend / $totalAttendances) * 100;
                        return number_format($attendancePercentage, 1) . '%';
                    } else {
                        return '0.0%';
                    }
                }),
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    LinkColumn::make('View')
                        ->title(fn ($row) => 'View')
                        ->location(fn ($row) => route('dashboard.student-attendance', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-sm btn-primary me-1',
                            ];
                        }),
                ]),
        ];
    }
}