<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Models\BlendingMeasure;
use App\Models\DailyMeasure;
use App\Models\GuacamoleMeasure;
use App\Models\Measure_category;
use App\Models\MetalDetectorMeasure;
use App\Models\MixingMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SafetyManagerController extends Controller
{
    public function verify_all_form_action(Request $request){
        $daily_measures_id = $request->daily_measures_id;
        $guacamole_items_status_check = DB::table('guacamole_items')
                                            ->leftJoin('guacamole_measures', function ($join) use ($daily_measures_id) {
                                                $join->on('guacamole_measures.guacamole_item_id', '=', 'guacamole_items.id')
                                                    ->where('guacamole_measures.daily_measure_id', '=', $daily_measures_id);
                                            })
                                            ->selectRaw('
                                                COUNT(guacamole_items.id) as total_items,
                                                SUM(CASE WHEN guacamole_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as submitted_items')
                                            ->first();

        if ($guacamole_items_status_check->total_items != $guacamole_items_status_check->submitted_items) {
            return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
        }
        $blending_items_status_check = DB::table('blending_items')
                                        ->leftJoin('blending_measures', function ($join) use ($daily_measures_id) {
                                            $join->on('blending_measures.blending_item_id', '=', 'blending_items.id')
                                                ->where('blending_measures.daily_measure_id', '=', $daily_measures_id);
                                        })
                                        ->selectRaw('
                                            COUNT(blending_items.id) as total_items,
                                            SUM(CASE WHEN blending_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as submitted_items')
                                        ->first();
        if ($blending_items_status_check->total_items != $blending_items_status_check->submitted_items) {
            return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
        }
        $mix_items_status_check = DB::table('mixing_items')
                                    ->leftJoin('mixing_measures', function ($join) use ($daily_measures_id) {
                                        $join->on('mixing_measures.mixing_item_id', '=', 'mixing_items.id')
                                            ->where('mixing_measures.daily_measure_id', '=', $daily_measures_id);
                                    })
                                    ->selectRaw('
                                        COUNT(mixing_items.id) as total_items,
                                        SUM(CASE WHEN mixing_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as submitted_items')
                                    ->first();
        if ($mix_items_status_check->total_items != $mix_items_status_check->submitted_items) {
            return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
        }
        $md_items_status_check = DB::table('metal_detector_items')
                                    ->leftJoin('metal_detector_measures', function ($join) use ($daily_measures_id) {
                                        $join->on('metal_detector_measures.metal_detector_item_id', '=', 'metal_detector_items.id')
                                            ->where('metal_detector_measures.daily_measure_id', '=', $daily_measures_id);
                                    })
                                    ->selectRaw('
                                        COUNT(metal_detector_items.id) as total_items,
                                        SUM(CASE WHEN metal_detector_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as submitted_items')
                                    ->first();
        if ($md_items_status_check->total_items != $md_items_status_check->submitted_items) {
            return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
        }

        $update_blending_item = BlendingMeasure::where('daily_measure_id', $daily_measures_id)
                                                ->update(['status' => 'Verified', 'reviewed_by'=> Auth::user()->id]);
        $update_mix_item = MixingMeasure::where('daily_measure_id', $daily_measures_id)
                                                ->update(['status' => 'Verified', 'reviewed_by'=> Auth::user()->id]);
        $update_md_item = MetalDetectorMeasure::where('daily_measure_id', $daily_measures_id)
                                                ->update(['status' => 'Verified', 'reviewed_by'=> Auth::user()->id]);
        $update_guacamole_item = GuacamoleMeasure::where('daily_measure_id', $daily_measures_id)
                                                ->update(['status' => 'Verified', 'reviewed_by'=> Auth::user()->id]);
        $update_daily_measure = DailyMeasure::where('id', $daily_measures_id)
                                                ->update(['status' => 'completed', 'is_lock' => '1']);
        return redirect()->back()->with('success', 'You have successfully verified all the process');
        // dd($md_items_status_check, $mix_items_status_check, $guacamole_items_status_check, $blending_items_status_check ,'TEST', $request->all());
    }

    public function verify_by_id_action(Request $request){
        $table_name = $request->table_name;
        $table_id = $request->table_id;
        DB::table($table_name)->where('id', $table_id)->update(['status' => 'Verified']);
        return redirect()->back()->with('success', 'You have successfully verified all the process');
    }

    public function unverified_measure_item($item_measure_id = NULL, $table_name = NULL, $daily_measure_id = NULL){
        $get_daily_measure_id = DB::table($table_name)->where('id', $item_measure_id)->select('daily_measure_id')->first()->daily_measure_id;
        DB::table($table_name)->where('id', $item_measure_id)->update(['status' => 'Submitted']);
        WebHookHelper::unverified_daily_measure_row($get_daily_measure_id);
        return redirect()->back()->with('success', "The measure item status has been updated. Now you can update the item details.");

    }

    public function item_master(){
        $get_all_category = Measure_category::all();
        $get_route = route('dashboard');
        return view('item-master.item-master', compact('get_all_category', 'get_route'));
    }
}
