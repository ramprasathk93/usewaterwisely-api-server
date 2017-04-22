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

    public function getTankWaterSizeForLocation(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'code' => 'required',
            'suburb' => 'required',
            'roof_area' => 'required',
            'household_number' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        $nearbyStation = Location::getStationNumber($bodyContent->code, $bodyContent->suburb);
        $rainfallAmount = Rainfall::getRainfallAmountForLocation($nearbyStation->pluck('station_number'));
        $rainfall = array_values($rainfallAmount->pluck('rainfall_amount')->toArray());
        $tankSize = Rainfall::calculateTankSize($rainfall, $bodyContent->roof_area, $bodyContent->household_number);
        /*foreach ($months as $id => $key) {
            $result[$key] = array(
                'month' => $months[$id],
                'rainfall'  => $rainfall[$id],
                'runoff' => $runoff[$id]
            );
        }*/

        return view('api.success', ["data" => ["tank_size" => $tankSize]]);
    }

    public function getTankWaterLevelForLocation(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'code' => 'required',
            'suburb' => 'required',
            'roof_area' => 'required',
            'household_number' => 'required',
            'tank_capacity' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        $nearbyStation = Location::getStationNumber($bodyContent->code, $bodyContent->suburb);
        $rainfallAmount = Rainfall::getRainfallAmountForLocation($nearbyStation->pluck('station_number'));
        $rainfall = array_values($rainfallAmount->pluck('rainfall_amount')->toArray());

        $params = [
            "rainfall" => $rainfall,
            "roof_area" => $bodyContent->roof_area,
            "household_number" => $bodyContent->household_number,
            "tank_capacity" => $bodyContent->tank_capacity
        ];

        $waterLevels = Rainfall::getWaterLevels($params);
        return view('api.success', ["data" => $waterLevels]);
    }
}
