<?php

namespace App\Http\Controllers\Tank;

use App\Models\Forecast;
use App\Models\Location;
use App\Models\Rainfall;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidRequestException;

class WaterlevelController extends Controller
{
    public function getLocationSuggestions(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'code' => 'sometimes|required|max:4|min:2',
            'location' => 'sometimes|required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        if (isset($bodyContent->location)){
            $suburbs = Location::getSuburb($bodyContent->location);
            return view('api.success', ["data" => ["locations" => $suburbs->pluck('suburb_code','suburb_name')]]);
        }

        if (isset($bodyContent->code)){
            $suburbs = Location::getSuburbByCode($bodyContent->code);
            return view('api.success', ["data" => ["locations" => $suburbs->pluck('suburb_code','suburb_name')]]);


        }
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
