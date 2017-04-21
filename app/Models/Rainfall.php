<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Rainfall extends Model
{
    protected $table = 'rainfall';

    public static function getRainfallAmountForLocation($location_id)
    {
        try{
            return Rainfall::where('station_number', '=', $location_id)->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public static function calculateTankSize($rainfall, $roofArea, $household)
    {

        //$runoff = array();
        $waterLevels = array();
        $demand = $household * 160 * 30;
        $volume = 0;
        for($size = 500; $size <= 10000; $size += 500){
            foreach ($rainfall as $amount){
                $runoffAmount = 0.80 * ($amount - 2) * $roofArea;
                $volume = $volume + ($runoffAmount - $demand);
                $overflow = $volume - $size;
                if ($overflow < 0)
                    break 1;
                else
                    array_push($waterLevels, $volume);
                }
            if (sizeof($waterLevels) == 12)
                break;
            }
        return $size;
    }

    public static function getWaterLevels($params)
    {
        $rainfall = $params["rainfall"];
        //dd($rainfall);
        //exit;
        $volume = 0;
        $demand = $params["household_number"] * 140 * 30;
        $tankSize = $params["tank_capacity"];
        $waterLevels = array();
        foreach ($rainfall as $amount){
            $runoffAmount = 0.80 * ($amount - 2) * $params["roof_area"];
            $volume = $volume + ($runoffAmount - $demand);
            if ($volume > $tankSize)
            {
                $overflow = $volume - $tankSize;
                $deficit = 0;
                $volume = $volume - $overflow;
            }
            elseif ($volume < 0)
            {
                $deficit = abs($volume);
                $overflow = 0;
                $volume = 0;
            }
            else
            {
                $overflow = 0;
                $deficit = 0;
            }
            array_push($waterLevels, array(
                "volume" => $volume,
                "overflow" => $overflow,
                "deficit" => $deficit
            ));
        }
        return $waterLevels;
    }
}
