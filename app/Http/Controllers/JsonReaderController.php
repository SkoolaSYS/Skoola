<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use App\Models\State;
use App\Models\Citie;
use App\Models\Postcode;


class JsonReaderController extends Controller
{
    public function readJson()
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
                $state = State::create(['name' => $stateData['name']]);

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

            return View::make('read-json')->with('jsonData', $json);
        } catch (GuzzleException $e) {
            // Handle exception and show error message
            $errorMessage = $e->getMessage();
            return response()->json(['error' => 'An error occurred while fetching the JSON data: ' . $errorMessage], 500);
        }
    }
}
