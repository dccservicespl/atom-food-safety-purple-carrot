<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MeasurementCategoryController extends Controller
{
    public function measurement_category($id = null){
        $decrypt_id = Crypt::decrypt($id);
        $get_all_category = Measure_category::all();
        $get_daily_measures = DailyMeasure::find($decrypt_id);
        if (Auth::user()->role_id == 2) {
            $get_guacamole_item_submitted_count = GuacamoleMeasure::where('status', 'Submitted')->where('daily_measure_id', $decrypt_id)->count();
            $get_guacamole_item_verified_count = GuacamoleMeasure::where('status', 'Verified')->where('daily_measure_id', $decrypt_id)->count();
            $total_guacamole = $get_guacamole_item_verified_count + $get_guacamole_item_submitted_count;

            $get_guacamole_item_counts = [
                'get_guacamole_item_submitted_count' => $get_guacamole_item_submitted_count,
                'get_guacamole_item_verified_count' => $get_guacamole_item_verified_count,
                'total_guacamole_item_submitted' => $total_guacamole,
                'guacamole_percentage' => $total_guacamole > 0 ? round(($get_guacamole_item_verified_count / $total_guacamole) * 100) : 0,
            ];

            // Blending
            $get_blending_item_submitted_count = BlendingMeasure::where('status', 'Submitted')->where('daily_measure_id', $decrypt_id)->count();
            $get_blending_item_verified_count = BlendingMeasure::where('status', 'Verified')->where('daily_measure_id', $decrypt_id)->count();
            $total_submitted_blending_item = $get_blending_item_submitted_count + $get_blending_item_verified_count;

            $get_blending_item_counts = [
                'get_blending_item_submitted_count' => $get_blending_item_submitted_count,
                'get_blending_item_verified_count' => $get_blending_item_verified_count,
                'total_submitted_blending_item' => $total_submitted_blending_item,
                'blending_percentage' => $total_submitted_blending_item > 0 ? round(($get_blending_item_verified_count / $total_submitted_blending_item) * 100) : 0,
            ];

            // Mixing
            $get_mixing_item_submitted_count = MixingMeasure::where('status', 'Submitted')->where('daily_measure_id', $decrypt_id)->count();
            $get_mixing_item_verified_count = MixingMeasure::where('status', 'Verified')->where('daily_measure_id', $decrypt_id)->count();
            $total_submitted_mixing_item = $get_mixing_item_submitted_count + $get_mixing_item_verified_count;

            $get_mixing_item_counts = [
                'get_mixing_item_submitted_count' => $get_mixing_item_submitted_count,
                'get_mixing_item_verified_count' => $get_mixing_item_verified_count,
                'total_submitted_mixing_item' => $total_submitted_mixing_item,
                'mixing_percentage' => $total_submitted_mixing_item > 0 ? round(($get_mixing_item_verified_count / $total_submitted_mixing_item) * 100) : 0,
            ];

            // Metal Detector
            $get_md_item_submitted_count = MetalDetectorMeasure::where('status', 'Submitted')->where('daily_measure_id', $decrypt_id)->count();
            $get_md_item_verified_count = MetalDetectorMeasure::where('status', 'Verified')->where('daily_measure_id', $decrypt_id)->count();
            $total_submitted_md_item = $get_md_item_submitted_count + $get_md_item_verified_count;

            $get_md_item_counts = [
                'get_md_item_submitted_count' => $get_md_item_submitted_count,
                'get_md_item_verified_count' => $get_md_item_verified_count,
                'total_submitted_md_item' => $total_submitted_md_item,
                'md_percentage' => $total_submitted_md_item > 0 ? round(($get_md_item_verified_count / $total_submitted_md_item) * 100) : 0,
            ];

            // Final data array
            $data_for_fsm = array(
                'get_guacamole_item_counts' => $get_guacamole_item_counts,
                'get_blending_item_counts' => $get_blending_item_counts,
                'get_md_item_counts' => $get_md_item_counts,
                'get_mixing_item_counts' => $get_mixing_item_counts,
            );
        }else{
            $data_for_fsm = array(
                'get_guacamole_item_counts' => '',
                'get_blending_item_counts' => '',
                'get_md_item_counts' => '',
                'get_mixing_item_counts' => '',
            );
        }



        $get_route = route('dashboard');
        return view('measurement-category.index', compact('get_route','get_daily_measures', 'data_for_fsm', 'get_all_category', 'id', 'get_daily_measures'));
    }

    public function add_measurement_action(Request $request){
        try {

            $request->validate([
                'date' => 'required|date|unique:daily_measures,measure_date',
            ]);

            $measure_date = $request->date;
            $start_time = date('H:i:s');
            $end_time = '00:00:00';

            // if (DailyMeasure::where('status', 'pending')->exists()) {
            //     return redirect()->back()->with('error', "There are pending Food Safety Records in your queue. You are allowed to submit new Records once you complete all previous Food Safety Records.");
            // }

            if (DailyMeasure::where('measure_date', $measure_date)->exists()) {
                return redirect()->back()->with('error', "You already have a Measurement Date on this date.");
            }

            $add_data_daily_measures = new DailyMeasure();
            $add_data_daily_measures->measure_date = $measure_date;
            $add_data_daily_measures->start_time = $start_time;
            $add_data_daily_measures->end_time = $end_time;
            $add_data_daily_measures->status = "pending";
            $add_data_daily_measures->is_lock = false;
            $add_data_daily_measures->created_by = Auth::user()->id;
            $add_data_daily_measures->save();
            return redirect()->back()->with('success', 'Congratulation! You successfully add a new Measurement Date.');
        } catch (\Throwable $th) {
            Log::error("Error create daily measure=>Date".date('Y-m-d H:i:s').":: =>".$th->getMessage());
            return redirect()->back()->with('error', "Something went wrong:".$th->getMessage());

        }
    }

    public function create_new_batch($measurement_id = null){
        $get_measure_details = BlendingMeasure::find($measurement_id);

        $generate_new_batch_no = BlendingMeasure::where('daily_measure_id', $get_measure_details->daily_measure_id)
                                                ->where('blending_item_id', $get_measure_details->blending_item_id)
                                                ->count() + 1;

        $add_new_record = new BlendingMeasure();
        $add_new_record->daily_measure_id = $get_measure_details->daily_measure_id;
        $add_new_record->measure_date = $get_measure_details->measure_date;
        $add_new_record->measure_time = $get_measure_details->measure_time;
        $add_new_record->blending_item_id = $get_measure_details->blending_item_id;
        $add_new_record->created_by = Auth::user()->id;
        $add_new_record->batch_no = $generate_new_batch_no;
        $add_new_record->save();
        return redirect()->route('blending_details', [Crypt::encrypt($get_measure_details->blending_item_id), Crypt::encrypt($get_measure_details->daily_measure_id), $generate_new_batch_no]);
    }
}
