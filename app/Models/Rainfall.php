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

    public static function calculateRunoff($rainfall, $roofArea)
    {
        $runoff = array();
        foreach ($rainfall as $amount){
            $runoffAmount = 0.80 * ($amount - 2) * $roofArea;
            array_push($runoff, $runoffAmount);
        }
        return $runoff;
    }
}
