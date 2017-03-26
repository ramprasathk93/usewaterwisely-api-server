<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Forecast extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'location_id';

    public static function getIdForLocation($location)
    {
        try{
            $query = Forecast::where('location_name', 'LIKE', '%' . $location . '%');
            return $query->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public static function searchApiForLocation($location)
    {
        $api_key = config('usewaterwisely.willyforecast.apiKey');
        // https://api.willyweather.com.au/v2/{api key}/search.json?query=beach&limit=2
        $baseurl = config('usewaterwisely.willyforecast.url');

        $url = $baseurl . $api_key . '/search.json?query=' . $location . '&limit=2';
        //dd($url);
        //exit();
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
}
