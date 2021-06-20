<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\UserDataTrait;


use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherController extends Controller
{
    use UserDataTrait;


    public function setWeather(Request $request)
    {
        $data = $this->getDataUser($request);
        $city = ($request->city == 'finding')? $data['city']:$request->city;
        try {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL,
                'http://api.openweathermap.org/data/2.5/find?q=' . $city. '&type=like&APPID=' . env('MIX_OPENWEATHER_KEY'));
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $weatherData = json_decode(curl_exec($curl_handle), true);
            curl_close($curl_handle);
            if ($weatherData['cod'] == 200) {
                $weatherCorrectForm = $this->setCorrectForm($weatherData);
                $coords = $weatherData['list'][0]['coord'];
                return response()->json(["data" => $weatherCorrectForm, "coords" => $coords, 'city' => $city],);
            }
        } catch (Throwable $throwable) {
            return response()->json(["error" => "Location not found"]);
        }
    }


    private function setCorrectForm($weatherData)
    {
        $deg = null;
        if ($weatherData['list'][0]['wind']['deg'] >337.5) $deg =  'Northerly';
        else if ($weatherData['list'][0]['wind']['deg'] >292.5) $deg =  'North Westerly';
        else if($weatherData['list'][0]['wind']['deg'] >247.5)  $deg = 'Westerly';
        else if($weatherData['list'][0]['wind']['deg'] >202.5)  $deg = 'South Westerly';
        else if($weatherData['list'][0]['wind']['deg'] >157.5)  $deg = 'Southerly';
        else if($weatherData['list'][0]['wind']['deg'] >122.5)  $deg = 'South Easterly';
        else if($weatherData['list'][0]['wind']['deg'] >67.5)  $deg = 'Easterly';
        else if($weatherData['list'][0]['wind']['deg'] >22.5)$deg = 'North Easterly';
        else  $deg = 'Northerly';

        return [
            'temp' => round(($weatherData['list'][0]['main']['temp'] - 273.15)) . ' °C',
            'feels_like' => round(($weatherData['list'][0]['main']['feels_like'] - 273.15)) . ' °C',
            'humidity' => $weatherData['list'][0]['main']['humidity'].' %',
            'pressure' => round($weatherData['list'][0]['main']['pressure'] * 0.75) . ' mm Hg',
            'main' => $weatherData['list'][0]['weather'][0]['main'],
            'wind_deg' => $deg,
            'wind_speed' => $weatherData['list'][0]['wind']['speed'].'m/sec',
            'description' => $weatherData['list'][0]['weather'][0]['description']
        ];
    }
}
