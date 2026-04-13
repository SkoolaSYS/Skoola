<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AttendanceTable extends DataTableComponent
{
    protected $model = Attendance::class;
    public $attendanceId;
    public $remarks;
    public $year;
    public $month;



    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        $user = Auth::user();

        return Attendance::query()
            ->whereHas('student.guardians', function ($query) use ($user) {
                $query->where('ic', $user->ic); // match parent IC
            })
            ->when($this->columnSearch['student.name'] ?? null, function ($query, $studentName) {
                return $query->whereHas('student', function ($subquery) use ($studentName) {
                    $subquery->where('name', 'like', '%' . $studentName . '%');
                });
            })
            ->when($this->year, function ($query, $year) {
                return $query->whereYear('date', $year);
            })
            ->when($this->month, function ($query, $month) {
                return $query->whereMonth('date', $month);
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
            
        ];
    }

    public function editRemarks($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        $this->attendanceId = $attendanceId;
        $this->remarks = $attendance->remarks; // prefill existing remarks
        
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit Remarks',
            'modal' => 'edit-remarks',
            'args' => ['attendanceId' => $attendanceId],
            'lg'
        ]);
    }

    public function saveRemarks()
{
    $attendance = Attendance::find($this->attendanceId);
    if ($attendance) {
        $attendance->remarks = $this->remarks; // take whatever is typed
        $attendance->save();
        $this->dispatchBrowserEvent('close-x-modal', ['modal' => 'edit-remarks']);
        $this->emit('alert', ['type' => 'success', 'message' => 'Remarks updated']);
    }
}



    public function filters(): array
    {
        $years = range(date('Y'), 2020);
        $yearOptions = ['' => 'All Years'] + array_combine($years, $years);

        $monthOptions = [
            ''  => 'All Months',
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        return [
            SelectFilter::make('Year')
                ->options($yearOptions)
                ->filter(function ($builder, $value) {
                    if ($value) {
                        $this->year = $value;
                    }
                }),

            SelectFilter::make('Month')
                ->options($monthOptions)
                ->filter(function ($builder, $value) {
                    if ($value) {
                        $this->month = $value;
                    }
                }),
        ];
    }


    public function mount()
    {
        $this->year = date('Y');
        $this->month = date('m');
    }



}
