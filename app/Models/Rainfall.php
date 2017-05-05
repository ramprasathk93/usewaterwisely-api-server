<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Rainfall extends Model
{
    // table name
    protected $table = 'rainfall';

    /**
     * Eloquent query to get the rainfall amount given the station id
     *
     * @param int $location_id
     * @return Rainfall
     */
    public static function getRainfallAmountForLocation($location_id)
    {
        try{
            return Rainfall::where('station_number', '=', $location_id)->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * Calculating the best tank size given the parameters
     *
     * @param array $rainfall
     * @param int $roofArea
     * @param int $household
     * @return int $size
     */
    public static function calculateTankSize($rainfall, $roofArea, $household)
    {
        $demand = $household * 140 * 30; // 140L per person for 30 days
        $size = 500;
        $status = false;
        while($size < 50000 and $status != true) {
            $waterLevels = array();
            $volume = 0; // reset at month 1
            foreach ($rainfall as $amount) {
                // run-off calculation
                $runoffAmount = 0.80 * ($amount - 2) * $roofArea;
                // security of water calculation
                $volume = $volume + ($runoffAmount - $demand);
                // overflow condition
                if ($volume > $size)
                {
                    $overflow = $volume - $size;
                    $deficit = 0;
                    $volume = $volume - $overflow;
                }
                // deficit condition
                elseif ($volume < 0)
                {
                    $deficit = abs($volume);
                    $overflow = 0;
                    $volume = 0;
                }
                // standard condition
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
            // checking deficit and overflow
            $status = Rainfall::checkTankStatus($waterLevels);
            $size = $size + 500;
        }
        return $size-500;
    }

    /**
     * Calculate the water level for a tank throughout the year
     *
     * @param array $params
     * @return array $waterLevels
     */
    public static function getWaterLevels($params)
    {
        $rainfall = $params["rainfall"];
        $demand = $params["household_number"] * 140 * 30;
        //$tankSize = $params["tank_capacity"];
        $waterLevels = array();
        foreach ($rainfall as $amount){
            $runoffAmount = 0.80 * ($amount - 2) * $params["roof_area"];
            array_push($waterLevels, array(
                "volume" => $runoffAmount,
                "demand" => $demand
            ));
        }
        return $waterLevels;
    }

    /**
     * Check if there is a deficit or overflow in a year
     *
     * @param array $waterLevels
     * @return bool
     */
    public static function checkTankStatus($waterLevels)
    {
        $count = 0;
        foreach ($waterLevels  as $value){
            if ($value['overflow'] == 0 && $value['deficit'] == 0)
            {
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
