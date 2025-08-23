<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Student;

class ViewAttendance extends Component
{
    public $listeners = ['attendanceUpdated' => 'render'];

    public function render()
    {
        $parent = auth()->user();
        $students = Student::where('parent_id', auth()->id())
            ->pluck('name', 'id')->toArray();
        $attendanceList = Attendance::whereIn('student_id', array_keys($students))
            ->orderByDesc('id') // Sort in descending order based on the 'id' or 'created_at' column
            ->get();
        return view('livewire.view-attendance', compact('attendanceList'));
    }
}
