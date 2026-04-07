<?php

namespace App\Http\Controllers;

use App\Models\PurpleCarrotItemMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PortioningMeasureController extends Controller
{
    public function portioning_measurement_form(Request $request)
    {
        // $item_details = PurpleCarrotItemMst::with('category')
        //                                     ->where('id', Crypt::decrypt($request->item_id))
        //                                     ->where('status', 'active')
        //                                     ->first();
        $item_details = "";
        $get_route = route('portioning_measure_head');
        return view('portioning_measurement_form.portioning_measurement_form', compact('item_details','get_route'));
    }

    public function item_form(){
        $get_route = '';
        return view('portioning_measurement_form.item_form', compact('get_route'));
    }
    public function portioning_measure_head(){
        $get_route = route('work_type');
        return view('portioning_measurement_form.portioning_measure_head', compact('get_route'));
    }

    public function portioning_measure_data_upload(){
        $get_route = route('portioning_measure_dashboard');
        return view('portioning_measurement_form.portioning_measure_data_upload', compact('get_route'));
    }

    public function portioning_measure_dashboard(){
        $get_route = route('work_type');
        return view('portioning_measurement_form.portioning_measure_dashboard', compact('get_route'));
    }

    public function week_details(){
        $get_route = route('work_type');
        return view('pages.week_details', compact('get_route'));
    }
}
