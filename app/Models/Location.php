<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Location extends Model
{
    //
    protected $table = 'suburbs';

    public static function getSuburb($suburb)
    {
        try{
            $query = Location::where('suburb_name', 'LIKE', '%' . $suburb . '%');
            return $query->limit(5)
                         ->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public static function getSuburbByCode($code)
    {
        try{
            $query = Location::where('suburb_code', 'LIKE', $code . '%');
            return $query->limit(5)
                ->get();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public static function getStationNumber($code, $suburb)
    {
        try{
            $query = Location::where('suburb_code', $code);
            return $query->where('suburb_name', $suburb)
                         ->get();
        } catch (ModelNotFoundException $e){
            return null;
        }
    }

    public function stations()
    {
        return $this->belongsToMany('App\Models\Rainfall', 'rainfall');
    }
}
