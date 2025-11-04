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
    ->whereHas('student.parents', function ($query) use ($user) {
        $query->where('users.id', $user->id);
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
            Column::make(__('messages.studentname'), "student.name")
                ->searchable()
                ->sortable(),
            Column::make(__('messages.checkin'), "check_in")
                ->sortable(),
            Column::make(__('messages.checkout'), "check_out")
                ->sortable(),
            Column::make(__('messages.date'), "date")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make(__('messages.remarks'), "remarks")
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
