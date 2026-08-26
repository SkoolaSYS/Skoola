<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use App\Models\School;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class SchoolTable extends DataTableComponent
{
    protected $model = School::class;
    public $ppd_id;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("NO.", "id")
                ->sortable(),
            Column::make("School Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Total Student", 'id')
                ->format(function ($value, $row) {
                    $totalStudents = Student::where('school_id', $row->id)->count();
                    return $totalStudents;
                }),
            Column::make("Average Percentage", 'id')
                ->format(function ($value, $row) {
                    // Retrieve attendance data for the current school
                    $attendancesAttend = Attendance::whereHas('student', function ($query) use ($row) {
                        $query->where('school_id', $row->id);
                    })
                        ->where('status', 'attend')
                        ->count();

                    $attendancesAbsent = Attendance::whereHas('student', function ($query) use ($row) {
                        $query->where('school_id', $row->id);
                    })
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
                        ->location(fn ($row) => route('dashboard.school', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-sm btn-primary me-1',
                            ];
                        }),
                ]),
        ];
    }

    public function builder(): Builder
    {
        return School::query()
            ->where('district_id', $this->ppd_id);
    }
}