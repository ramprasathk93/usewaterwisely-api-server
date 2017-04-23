<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Location extends Model
{
    // table name
    protected $table = 'suburbs';

    /**
     * Eloquent query to get matching suburb names
     *
     * @param string $suburb
     * @return Location
     */
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

    /**
     * Eloquent query to get suburb by post code
     *
     * @param string $code
     * @return Location
     */
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

    /**
     * Eloquent model to get nearby station for suburb
     *
     * @param string $code
     * @param string $suburb
     * @return Location
     */
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

    /**
     * A stationcan have many suburbs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stations()
    {
        return $this->belongsToMany('App\Models\Rainfall', 'rainfall');
    }
}
