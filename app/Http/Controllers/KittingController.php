<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KittingController extends Controller
{
    public function kittingMeasureDateListing(){
        $get_route = route('kitting_measure_date_listing');
        return view('kitting.kittingMeasureDateListing', compact('get_route'));
    }

    public function meal_kit_type(){
        $get_route = route('meal_kit_type');
        return view('kitting.meal_kit_type', compact('get_route'));
    }
}
