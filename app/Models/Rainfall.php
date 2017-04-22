<?php

namespace App\Models;

use Carbon\Carbon;
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
        //$waterLevels = array();
        $demand = $household * 140 * 30;
        $size = 500;
        $status = false;
        while($size < 50000 and $status != true) {
            $waterLevels = array();
            $volume = 0;
            foreach ($rainfall as $amount) {
                $runoffAmount = 0.80 * ($amount - 2) * $roofArea;
                $volume = $volume + ($runoffAmount - $demand);
                if ($volume > $size) {
                    $overflow = $volume - $size;
                    $deficit = 0;
                    $volume = $volume - $overflow;
                } elseif ($volume < 0) {
                    $deficit = abs($volume);
                    $overflow = 0;
                    $volume = 0;
                } else {
                    $overflow = 0;
                    $deficit = 0;
                }
                array_push($waterLevels, array(
                    "volume" => $volume,
                    "overflow" => $overflow,
                    "deficit" => $deficit
                ));
            }
            $status = Rainfall::checkTankStatus($waterLevels);
            $size = $size + 500;
        }
        return $size-500;
    }

    public static function getWaterLevels($params)
    {
        $rainfall = $params["rainfall"];
        //$currentTime = Carbon::now();
        //$currentMonth = $currentTime->month;
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

    public static function checkTankStatus($waterLevels)
    {
        $count = 0;
        foreach ($waterLevels  as $value){
            if ($value['overflow'] == 0 && $value['deficit'] == 0){
                $count = $count + 1;
            }
        }
        if ($count == 12){
            return true;
        }
        else
            return false;
    }
}
