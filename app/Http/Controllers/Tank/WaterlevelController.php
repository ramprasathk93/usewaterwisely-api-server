<?php

namespace App\Http\Controllers\Tank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterlevelController extends Controller
{
    //
    public function getTankWaterLevel()
    {
        return view('api.success', ["data" => ["message" => "success"]]);
    }
}
