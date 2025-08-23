<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Citie;
use App\Models\Postcode;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MalaysiaState extends Component
{
    public $selectedState = null;
    public $selectedCity = null;
    public $selectedPostcode = null;
    public $cities;
    public $states;
    public $postcodes;

    public function mount($selectedPostcode = null)
    {
        $this->states = State::all();
        $this->cities = collect();
        $this->postcodes = collect();
        $this->selectedPostcode = $selectedPostcode;

        if (!is_null($selectedPostcode)) {
            $postcode = Postcode::find($selectedPostcode);
            if ($postcode) {
                $this->postcodes = Postcode::where('citie_id', $postcode->citie_id)->get();
                $this->cities = Citie::where('state_id', $postcode->citie->state_id)->get();
                $this->selectedState = $postcode->citie->state_id;
                $this->selectedCity = $postcode->citie_id;
            }
        }
    }

    public function render()
    {
        $users = User::find(auth()->user()->id);
        return view('livewire.malaysia-state');
    }

    public function updatedSelectedState($state_id)
    {
        $this->cities = Citie::where('state_id', $state_id)->get();
    }

    public function updatedSelectedCity($citie_id)
    {
        $this->postcodes = Postcode::where('citie_id', $citie_id)->get();
    }
}
