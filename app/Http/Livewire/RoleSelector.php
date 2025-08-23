<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\School;
use App\Models\State;
use Livewire\Component;
use Illuminate\Validation\Rule;

class RoleSelector extends Component
{
    public $selectedRole = null;
    public $selectedState = null;
    public $selectedPPD = null;
    public $selectedSchool = null;

    public function render()
    {
        $states = State::all();
        $ppds = District::all();
        $schools = School::all();
        return view('livewire.role-selector', [
            'states' => $states,
            'ppds' => $ppds,
            'schools' => $schools,
        ]);
    }

    public function updatedSelectedRole($value)
    {
        $this->validate([
            'selectedRole' => Rule::in(['3', '4', '5']), // Validate the selected role
            'selectedState' => $this->selectedRole == '3' ? 'required' : '',
            'selectedPPD' => $this->selectedRole == '4' ? 'required' : '',
            'selectedSchool' => $this->selectedRole == '5' ? 'required' : '',
        ]);
    }
}
