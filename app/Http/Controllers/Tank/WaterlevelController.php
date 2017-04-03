<?php

namespace App\Http\Controllers\Tank;

use App\Models\Forecast;
use App\Models\Rainfall;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidRequestException;

class WaterlevelController extends Controller
{
    public function getLocations(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        $suburbs = Forecast::getStationNames($bodyContent->location);
        return view('api.success', ["data" => ["locations" => $suburbs]]);
    }

    public function getForecastForLocation(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent,[
            'location_id' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        $forecast = Forecast::getForecastForLocationId($bodyContent->location_id);

        return view('api.success', ["data" => ["forecast" => json_decode($forecast)]]);
    }

    public function getTankWaterLevelForLocation(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'station_id' => 'required',
            'roof_area' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        $rainfallAmount = Rainfall::getRainfallAmountForLocation($bodyContent->station_id);
        $rainfall = array_values($rainfallAmount->pluck('rainfall_amount')->toArray());
        $months = array_values($rainfallAmount->pluck('month')->toArray());
        $runoff = Rainfall::calculateRunoff($rainfall, $bodyContent->roof_area);
        $result = array();
        foreach ($months as $id => $key) {
            $result[$key] = array(
                'month' => $months[$id],
                'rainfall'  => $rainfall[$id],
                'runoff' => $runoff[$id]
            );
        }

        return view('api.success', ["data" => $result]);
    }
}
