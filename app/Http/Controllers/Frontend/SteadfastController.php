<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SteadfastController extends Controller
{
    /**
     * Fetch and cache police stations (Districts and Thanas) from Steadfast.
     */
    public function getPoliceStations()
    {
        // Cache the result for 24 hours (1440 minutes) since this data rarely changes
        $policeStations = Cache::remember('steadfast_police_stations', 1440, function () {
            $apiKey = config('services.steadfast.api_key');
            $secretKey = config('services.steadfast.secret_key');
            $baseUrl = config('services.steadfast.base_url');

            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Api-Key'    => $apiKey,
                    'Secret-Key' => $secretKey,
                ])->timeout(10)->get($baseUrl . '/police_stations');

                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['status']) && $json['status'] === 'success') {
                        return $json['data'];
                    }
                }
                
                return [];
            } catch (\Exception $e) {
                \Log::error('Failed to fetch Steadfast police stations: ' . $e->getMessage());
                return [];
            }
        });

        return response()->json([
            'success' => true,
            'data' => $policeStations
        ]);
    }
}
