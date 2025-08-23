<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceTable extends DataTableComponent
{
    protected $model = Attendance::class;
    public $attendanceId;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        $user = Auth::user();

        return Attendance::query()
            ->whereHas('student', function ($query) use ($user) {
                $query->where('parent_id', $user->id);
            })
            ->when($this->columnSearch['student.name'] ?? null, function ($query, $studentName) {
                return $query->whereHas('student', function ($subquery) use ($studentName) {
                    $subquery->where('name', 'like', '%' . $studentName . '%');
                });
            });
    }

    public function columns(): array
    {
        return [
            Column::make("NO.", "id")
                ->sortable(),
            Column::make("Student Name", "student.name")
                ->searchable()
                ->sortable(),
            Column::make("Check in", "check_in")
                ->sortable(),
            Column::make("Check out", "check_out")
                ->sortable(),
            Column::make("Date", "date")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Remarks", "remarks")
                ->sortable(),
            Column::make('')
                ->label(function ($row, Column $column) {
                    $html = "<div class='btn-group'>";
                    $html .= "<a class='btn btn-primary btn-sm text-white' wire:click='editRemarks($row->id)'>Edit Remarks</a>";
                    $html .= "<div>";
                    return $html;
                })->html(),
        ];
    }

    public function editRemarks($attendanceId)
    {
        $this->dispatchBrowserEvent('open-x-modal', ['title' => 'Edit Remarks', 'modal' => 'edit-remarks', 'args' => ['attendanceId' => $attendanceId],'lg']);
    }
}
