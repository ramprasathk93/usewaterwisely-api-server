<?php

namespace App\Http\Controllers\Tank;

use App\Models\Forecast;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidRequestException;

class WaterlevelController extends Controller
{
    //
    public function getTankWaterLevel(Request $request)
    {
        $bodyContent = json_decode($request->getContent());

        $validator = Validator::make((array)$bodyContent, [
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException($validator);
        }

        //$suburbs = Forecast::getIdForLocation($bodyContent->location);
        //if ($suburbs == null) {
            $suburbs = Forecast::searchApiForLocation($bodyContent->location);
        //}

        return view('api.success', ["data" => ["message" => json_decode($suburbs)]]);
    }
}
