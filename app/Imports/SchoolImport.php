<?php

namespace App\Imports;

use App\Models\School;
use App\Models\District;
use App\Models\State;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchoolImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $NEGERI = trim($row['negeri']);

        // Map Excel values to DB values
        $stateMap = [
            'penang' => 'Pulau Pinang',
        ];

        // Normalize
        $normalized = strtolower($NEGERI);

        // Use mapped name if exists, otherwise ucfirst
        $stateName = $stateMap[$normalized] ?? ucfirst($normalized);

        // Find state in DB
        $state = State::whereRaw('LOWER(name) = ?', [strtolower($stateName)])->first();

        if (!$state) {
            // Skip this row if state not found
            return null;
        }

        $PPD = trim($row['ppd']);
        $district = District::updateOrCreate(
            ['ppd' => $PPD, 'state_id' => $state->id]
        );

        $NAMASEKOLAH = trim($row['namasekolah']);

        return new School([
            'state_id'    => $state->id,
            'district_id' => $district->id,
            'name'        => $NAMASEKOLAH,
        ]);
    }

    public function headingRow(): int
    {
        return 2;
    }
}
