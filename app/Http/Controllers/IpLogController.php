<?php

namespace App\Http\Controllers;

use App\Models\IpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IpLogController extends Controller
{
    public function store(Request $request)
    {
        // Get IP address from the request
        $ip = $request->ip();
        $visitedUrl = $request->input('visited_url'); // Get the visited URL from the request
        $userAgent = $request->header('User-Agent'); // Get User-Agent from the request

        // Initialize geolocation data
        $city = null;
        $region = null;
        $country = null;
        $latitude = null;
        $longitude = null;
        $isp = null;

        // Use a geolocation service (e.g., IPinfo, ipstack) to get more details
        $apiKey = config('services.ipinfo.token'); // Ensure your config/services.php has ipinfo token setup
        $response = Http::get("https://ipinfo.io/{$ip}?token={$apiKey}");

        if ($response->successful()) {
            $data = $response->json();

            // Extract necessary information from geolocation service
            $city = $data['city'] ?? null;
            $region = $data['region'] ?? null;
            $country = $data['country'] ?? null;
            $location = $data['loc'] ?? null;
            $isp = $data['org'] ?? null;

            // If location data exists, split into latitude and longitude
            if ($location) {
                [$latitude, $longitude] = explode(',', $location);
            }
        }

        // Store the IP address, geolocation data, visited URL, and user agent in the database
        IpLog::create([
            'ip_address' => $ip,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'isp' => $isp,
            'user_agent' => $userAgent,
            'visited_url' => $visitedUrl, // Store the visited URL
        ]);

        return response()->json(['message' => 'IP log saved successfully'], 201);
    }
}
