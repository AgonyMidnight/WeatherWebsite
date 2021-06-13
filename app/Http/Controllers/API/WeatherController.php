<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherController extends Controller
{
    use UserDataTrait;


    public function setWeather(Request $request)
    {
        //return response()->json(["data" => $this->getData()]);
        try {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, 'http://api.openweathermap.org/data/2.5/find?q=' . $request->city . '&type=like&APPID=' . env('MIX_OPENWEATHER_KEY'));
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $weatherData = json_decode(curl_exec($curl_handle), true);
            if ($weatherData['cod'] == 200) {
                $weatherCorrectForm = $this->setCorrectForm($weatherData);
            }
            $weatherMap = $this->setMapWeather ($request);
            curl_close($curl_handle);
            return response()->json(["data" => $weatherCorrectForm, "map" => $weatherMap]);;
        } catch (Throwable $throwable) {
            return 1;
        }
    }

    public function setMapWeather(Request $request)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'https://tile.openweathermap.org/map/temp_new/10/'.round($request->latitude).'/'.round($request->longitude).'.png?appid' . env('MIX_OPENWEATHER_KEY'));
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $maps = json_decode(curl_exec($curl_handle), true);
        curl_close($curl_handle);
        return $maps;
    }

    private function setCorrectForm($weatherData)
    {
        return [
            'temp' => round(($weatherData['list'][0]['main']['temp'] - 273.15)) . ' °C',
            'feels_like' => round(($weatherData['list'][0]['main']['feels_like'] - 273.15)) . ' °C',
            'humidity' => $weatherData['list'][0]['main']['humidity'],
            'pressure' => round($weatherData['list'][0]['main']['pressure'] * 0.75) . ' mm Hg',
            'rain' => $weatherData['list'][0]['rain'],
            'snow' => $weatherData['list'][0]['snow'],
            'main' => $weatherData['list'][0]['weather'][0]['main'],
            'wind_deg' => $weatherData['list'][0]['wind']['deg'],
            'wind_speed' => $weatherData['list'][0]['wind']['deg']
        ];

    }
}
