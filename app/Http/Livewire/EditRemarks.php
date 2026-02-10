<?php

namespace App\Http\Livewire;

use App\Models\Attendance;
use Livewire\Component;

class EditRemarks extends Component
{
    public Attendance $attendance;
    public $attendanceId;

    public function mount(Attendance $attendanceId)
    {
        $this->attendance = $attendanceId;
    }
    
    public function update()
    {
        $this->validate();
        $this->attendance->save();
    }
    public function render()
    {
        return view('livewire.edit-remarks');
    }

    protected function rules(): array
    {
        return [
            'attendance.remarks' => ['required', 'string'],
            // 'attendance.remarks_desc' => ['nullable','string'] 
        ];
    }

}
