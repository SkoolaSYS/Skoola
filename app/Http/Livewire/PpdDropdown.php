<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\School;
use App\Models\State;
use App\Models\District;

class PpdDropdown extends Component
{
    public $states;
    public $districts;
    public $schools;
    public $selectedState = NULL;
    public $selectedDistrict = NULL;
    public $selectedSchool = null;
    public $prefix;
    public $key;

    public function mount($selectedSchool = null, $prefix = '', $key = null)
    {
        $this->prefix = $prefix;
        $this->key = $key;
        $this->states = State::all();
        $this->districts = collect();
        $this->schools = collect();
        $this->selectedSchool = $selectedSchool;

        if (!is_null($selectedSchool)) {
            $school = School::find($selectedSchool);
            if ($school) {
                $this->schools = School::where('district_id', $school->district_id)->get();
                $this->districts = District::where('state_id', $school->district->state_id)->get();
                $this->selectedState = $school->district->state_id;
                $this->selectedDistrict = $school->district_id;
            }
        }
    }

    public function render()
    {
        return view('livewire.ppd-dropdown', [
            'districts' => $this->districts,
            'schools' => $this->schools,
        ]);
    }

    public function updatedSelectedState($state)
{
    $this->districts = $state ? District::where('state_id', $state)->get() : collect();
    $this->selectedDistrict = null; // reset
    $this->schools = collect();      // reset
    $this->selectedSchool = null;    // reset
}

public function updatedSelectedDistrict($districtId)
{
    $this->schools = $districtId ? School::where('district_id', $districtId)->get() : collect();
    $this->selectedSchool = null;    // reset
}

}
