<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExternalApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * Fetch weather forecast for an event's location and date.
     * Uses Open-Meteo API (Free, no key required).
     */
    public function eventWeather(Request $request)
    {
        $request->validate([
            'lat'  => 'required|numeric',
            'lon'  => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'city' => 'required|string',
        ]);

        try {
            $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude'  => $request->lat,
                'longitude' => $request->lon,
                'daily'     => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max,weathercode',
                'timezone'  => 'auto',
                'start_date' => $request->date,
                'end_date'   => $request->date,
            ]);

            if ($response->failed()) {
                return $this->errorResponse('Weather service temporarily unavailable. Please try again later.', 502);
            }

            $weatherData = $response->json();
            
            if (!isset($weatherData['daily']['weathercode'][0])) {
                return $this->errorResponse('No weather data found for the selected date.', 404);
            }

            $code = $weatherData['daily']['weathercode'][0];
            $tempMax = $weatherData['daily']['temperature_2m_max'][0];
            $tempMin = $weatherData['daily']['temperature_2m_min'][0];
            $rainChance = $weatherData['daily']['precipitation_probability_max'][0];

            // Map weather code to description and emoji
            [$condition, $emoji] = $this->mapWeatherCode($code);

            // Generate advice
            $advice = "Great weather for an event!";
            if ($rainChance > 70) {
                $advice = "High chance of rain. Recommend bringing a raincoat.";
            } elseif ($rainChance > 40) {
                $advice = "Some chance of rain. Consider bringing an umbrella.";
            } elseif ($tempMax > 35) {
                $advice = "Very hot day. Ensure water is available for attendees.";
            }

            $data = [
                'city'            => $request->city,
                'date'            => $request->date,
                'temperature_max' => $tempMax,
                'temperature_min' => $tempMin,
                'rain_chance'     => $rainChance,
                'condition'       => $condition,
                'emoji'           => $emoji,
                'advice'          => $advice,
            ];

            return $this->successResponse($data, 'Weather forecast retrieved successfully.');

        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred while fetching weather data.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Map Open-Meteo WMO Weather interpretation codes to human-readable format.
     */
    private function mapWeatherCode(int $code): array
    {
        return match (true) {
            $code === 0 => ['Clear Sky', '☀️'],
            in_array($code, [1, 2, 3]) => ['Partly Cloudy', '⛅'],
            in_array($code, [45, 48]) => ['Foggy', '🌫️'],
            in_array($code, [51, 53, 55, 61, 63, 65]) => ['Rainy', '🌧️'],
            in_array($code, [71, 73, 75]) => ['Snowy', '❄️'],
            in_array($code, [80, 81, 82]) => ['Rain Showers', '🌦️'],
            in_array($code, [95, 96, 99]) => ['Thunderstorm', '⛈️'],
            default => ['Cloudy', '🌥️'],
        };
    }
}
