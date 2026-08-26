<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use App\Models\State;
class StateCityPostcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $githubUrl = 'https://raw.githubusercontent.com/AsyrafHussin/malaysia-postcodes/master/all.json';

        $client = new Client([
            RequestOptions::VERIFY => storage_path('cacert.pem')
        ]);

        try {
            $response = $client->request('GET', $githubUrl);
            $json = $response->getBody()->getContents();
            $data = json_decode($json, true);

            foreach ($data['state'] as $stateData) {
                $state = State::updateOrCreate(['name' => $stateData['name']]);

                if (isset($stateData['city'])) {
                    foreach ($stateData['city'] as $cityData) {
                        $city = $state->cities()->create(['name' => $cityData['name']]);

                        if (isset($cityData['postcode'])) {
                            foreach ($cityData['postcode'] as $postcode) {
                                $city->postcodes()->create(['name' => $postcode]);
                            }
                        }
                    }
                }
            }
        } catch (GuzzleException $e) {
            // Handle exception and show error message
            $errorMessage = $e->getMessage();
            echo 'An error occurred while fetching the JSON data: ' . $errorMessage;
        }
    }
}
