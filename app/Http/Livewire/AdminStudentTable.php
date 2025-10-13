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

    public function toggleStatus($studentId)
    {
        $student = Student::find($studentId);

        if ($student) {
            $student->status = $student->status === 'Active' ? 'Inactive' : 'Active';
            $student->save();
        }
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

        Column::make('Status', 'status')
            ->format(fn($value) =>
                $value === 'Active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>'
            )
            ->html(),

        Column::make('Actions')
            ->label(function ($row) {
                $toggleButton = $row->status === 'Active'
                    ? "<button wire:click='toggleStatus({$row->id})' class='btn btn-sm btn-danger me-1'>Deactivate</button>"
                    : "<button wire:click='toggleStatus({$row->id})' class='btn btn-sm btn-success me-1'>Activate</button>";

                return "
                    <a href='" . route('dashboard.student-attendance', $row) . "' class='btn btn-sm btn-primary me-1'>View</a>
                    <a href='" . route('school.students.edit', $row) . "' class='btn btn-sm btn-warning me-1'>Edit</a>
                    {$toggleButton}
                ";
            })
            ->html(),
    ];
}

}
