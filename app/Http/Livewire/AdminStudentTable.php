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

        Column::make(__('messages.studentname'), 'name')
            ->sortable()
            ->searchable(),

        Column::make(__('messages.attendancepercentage'), 'id')
            ->format(function ($value, $row) {
                $attendancesAttend = Attendance::where('student_id', $row->id)
                    ->where('status', 'attend')
                    ->count();

                $attendancesAbsent = Attendance::where('student_id', $row->id)
                    ->where('status', 'absent')
                    ->count();

                $totalAttendances = $attendancesAttend + $attendancesAbsent;

                return $totalAttendances > 0
                    ? number_format(($attendancesAttend / $totalAttendances) * 100, 1) . '%'
                    : '0.0%';
            }),

        Column::make('Status', 'status')
            ->format(fn($value) =>
                $value === 'Active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>'
            )
            ->html(), // ✅ correct usage here (on the Column definition)

        Column::make(__('messages.action'))
    ->label(function ($row) {
        $user = Auth::user();

        // Always show "View" button
        $buttons = "<button wire:click='showStudentModal({$row->id})' class='btn btn-sm btn-primary me-1'>" . __('messages.view') . "</button>";

        // Show Details button only to admin/school
        if ($user->hasRole(['admin', 'school'])) {
            $buttons .= "<a href='" . route('school.students.details', $row->id) . "' class='btn btn-sm btn-info me-1'>" . __('messages.details') . "</a>";
        }

        // Show Edit + Toggle for admin/school roles
        if ($user->hasRole(['admin', 'school'])) {
            $toggleButton = $row->status === 'Active'
                ? "<button wire:click='toggleStatus({$row->id})' class='btn btn-sm btn-danger me-1'>" . __('messages.deactivate') . "</button>"
                : "<button wire:click='toggleStatus({$row->id})' class='btn btn-sm btn-success me-1'>" . __('messages.activate') . "</button>";

            $buttons .= "
                <a href='" . route('school.students.edit', $row) . "' class='btn btn-sm btn-warning me-1'>" . __('messages.edit') . "</a>
                {$toggleButton}
            ";
        }

        return $buttons;
    })
    ->html(),

    ];
}


public $selectedStudent;
public $attendanceData = [];

public function showStudentModal($studentId)
{
    $student = Student::find($studentId);
    $attendCount = Attendance::where('student_id', $studentId)->where('status', 'attend')->count();
    $absentCount = Attendance::where('student_id', $studentId)->where('status', 'absent')->count();

    $payload = [
        'attendanceData' => [$attendCount, $absentCount],
        'studentName'    => $student ? $student->name : null,
        'detailUrl'      => $student ? route('dashboard.student-attendance', $student->id) : null,
    ];

    // dispatch payload with details
    $this->dispatchBrowserEvent('open-student-modal', $payload);
}






}
