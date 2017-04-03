<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Forecast extends Model
{
    protected $table = 'stations';
    protected $primaryKey = 'station_id';

    public static function getStationNames($location)
    {
        try{
            $query = Forecast::where('station_name', 'LIKE', '%' . $location . '%');
            return $query->limit(3)
                         ->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public static function searchApiForLocation($location)
    {
        $api_key = config('usewaterwisely.willyforecast.apiKey');
        // https://api.willyweather.com.au/v2/{api key}/search.json?query=beach&limit=2
        $baseUrl = config('usewaterwisely.willyforecast.url');

        $url = $baseUrl . $api_key . '/search.json?query=' . $location . '&limit=2';
        $ch = curl_init();
        //$timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    public static function getForecastForLocationId($location_id)
    {
        $api_key = config('usewaterwisely.willyforecast.apiKey');
        // https://api.willyweather.com.au/v2/{api key}/locations/1215/weather.json?forecasts=rainfall&days=7
        $baseUrl = config('usewaterwisely.willyforecast.url');

        $url = $baseUrl . $api_key . '/locations/' . $location_id .'/weather.json?forecasts=rainfall&days=7';
        $ch = curl_init();
        //dd($url);
        //exit();
        //$timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;

    }

    public function stations()
    {
        return $this->belongsToMany('App\Models\Rainfall', 'rainfall');
    }
}
