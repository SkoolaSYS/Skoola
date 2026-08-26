<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
                Validator::make($input, [
            'name'         => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', 'unique:users'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_num'    => ['required', 'string', 'max:255'],
            'ic'           => ['nullable', 'string', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:255'],
            'occupation'   => ['nullable', 'string', 'max:255'],
            'address'      => ['nullable', 'string', 'max:255'],

            // fix field names to match DB
            'state_id'     => ['nullable', 'exists:states,id'],
            'citie_id'     => ['nullable', 'exists:cities,id'],
            'postcode_id'  => ['nullable', 'exists:postcodes,id'],

            'password'     => $this->passwordRules(),
            'terms'        => Jetstream::hasTermsAndPrivacyPolicyFeature()
                                ? ['accepted', 'required']
                                : '',
        ])->validate();

        

        return User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'email' => $input['email'],
            'phone_num' => $input['phone_num'],
            'ic' => $input['ic'] ?? null,
            'relationship' => $input['relationship'] ?? null,
            'occupation' => $input['occupation'] ?? null,
            'address' => $input['address'] ?? null,
            'state_id'     => $input['state_id'] ?? null,
            'citie_id'      => $input['citie_id'] ?? null,
            'postcode_id'  => $input['postcode_id'] ?? null,
            'password' => Hash::make($input['password']),
        ]);
    }
}
