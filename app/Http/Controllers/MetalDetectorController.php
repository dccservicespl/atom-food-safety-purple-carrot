<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Exports\MetalDetectorMeasuresExport;
use App\Models\DailyMeasure;
use App\Models\Measure_category;
use App\Models\MetalDetectorItem;
use App\Models\MetalDetectorLog;
use App\Models\MetalDetectorMeasure;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MetalDetectorController extends Controller
{
    public function metal_detector_list($category_id = null, $date_id = null)
    {
        $category_id_dec = Crypt::decrypt($category_id);
        $date_id_dec = Crypt::decrypt($date_id);
        $daily_measures = Measure_category::where('id', $category_id_dec)->first();
        $get_the_date_details = DailyMeasure::where('id', $date_id_dec)->first();

        $get_the_md_items = DB::table('metal_detector_items')
            ->leftJoin('metal_detector_measures', function ($join) use ($date_id_dec) {
                $join->on('metal_detector_measures.metal_detector_item_id', '=', 'metal_detector_items.id')
                    ->where('metal_detector_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->leftJoin('users as technician', 'technician.id', 'metal_detector_measures.created_by')
            ->leftJoin('users as manager', 'manager.id', 'metal_detector_measures.reviewed_by')
            ->select('metal_detector_items.item_name','metal_detector_items.item_unit', 'metal_detector_items.weight', 'metal_detector_items.id as md_items_id', 'metal_detector_measures.status', 'technician.name as technician_name', 'manager.name as manager_name')
            ->where('metal_detector_items.status', 'Active')
            ->get();

        $all_md_item_count = DB::table('metal_detector_items')
            ->leftJoin('metal_detector_measures', function ($join) use ($date_id_dec) {
                $join->on('metal_detector_measures.metal_detector_item_id', '=', 'metal_detector_items.id')
                    ->where('metal_detector_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->selectRaw('COUNT(metal_detector_items.id) as total_items,
                                                    SUM(CASE WHEN metal_detector_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as completed_status')
            ->first();
        $get_route = route('measurement_category', [$date_id]);
        return view('metal_detector_measures.index', compact('get_route', 'daily_measures', 'get_the_md_items', 'get_the_date_details', 'all_md_item_count', 'date_id'));
    }


    public function metal_detective_details($item_id = null, $date_id = null)
    {
        $item_id_decrypt = Crypt::decrypt($item_id);
        $date_id_decrypt = Crypt::decrypt($date_id);

        $get_the_measure_date = DailyMeasure::where('id', $date_id_decrypt)->select('measure_date', 'id')->first();
        $get_the_item_details = MetalDetectorItem::where('id', $item_id_decrypt)->first();

        // find blending_measures record
        $find_blending_measure_by_id = MetalDetectorMeasure::where('metal_detector_item_id', $item_id_decrypt)
            ->where('daily_measure_id', $get_the_measure_date->id)
            ->first();
        $get_route = route('metal_detector_list', [Crypt::encrypt(7), $date_id]);
        if ($find_blending_measure_by_id) {
            return view('metal_detector_measures.metal-detective-measure-edit', compact('get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date', 'find_blending_measure_by_id'));
        } else {
            return view('metal_detector_measures.metal-detective-measor', compact('get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date'));
        }
    }

    public function metal_measure_action(Request $request)
    {
        try {
            // dd($request->all());
            $daily_measure_id_decrypt = Crypt::decrypt($request->mixing_measure_id);
            $metal_item_id = Crypt::decrypt($request->mixing_item_id);

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();

            $add_metal_detector_measures = new MetalDetectorMeasure();
            $add_metal_detector_measures->daily_measure_id = $daily_measure_id_decrypt;
            $add_metal_detector_measures->measure_date = $get_details_from_daily_measure->measure_date;
            $add_metal_detector_measures->measure_time = $get_details_from_daily_measure->start_time;
            $add_metal_detector_measures->metal_detector_item_id = $metal_item_id;
            $add_metal_detector_measures->md_model_result = $request->md_model_result;
            $add_metal_detector_measures->mm_2_fe = $request->mm_2_fe;
            $add_metal_detector_measures->mm_3_nfe = $request->mm_3_nfe;
            $add_metal_detector_measures->mm_4_ss = $request->mm_4_ss;
            $add_metal_detector_measures->confirm_label = $request->confirm_label;
            $add_metal_detector_measures->comments = $request->comments;
            $add_metal_detector_measures->initial = $request->initial;
            $add_metal_detector_measures->status = "Submitted";
            $add_metal_detector_measures->created_by = Auth::user()->id;
            $add_metal_detector_measures->save();

            return redirect()->route('metal_detector_list', [Crypt::encrypt(7), $request->mixing_measure_id])->with('success', 'Metal Detector process has been added successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('metal_detector_list', [Crypt::encrypt(7), $request->mixing_measure_id])->with('error', 'Error:' . $th->getMessage());
        }
    }

    public function metal_measure_update_action(Request $request){
        try {
            // dd($request->all());
            $daily_measure_id_decrypt = $request->daily_measure_id;
            $blending_item_id_decrypt = $request->metal_item_id;

            $get_blending_item_details = MetalDetectorItem::where('id', $blending_item_id_decrypt)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $update_metal_measures = MetalDetectorMeasure::find($request->id);

            if ($this->old_and_new_measure_data($request, $update_metal_measures)) {
                $add_new_record_into_log = new MetalDetectorLog();
                $add_new_record_into_log->daily_measure_id = $daily_measure_id_decrypt;
                $add_new_record_into_log->measure_date = $get_details_from_daily_measure->measure_date;
                $add_new_record_into_log->measure_time = $get_details_from_daily_measure->start_time;
                $add_new_record_into_log->metal_detector_item_id = $blending_item_id_decrypt;
                $add_new_record_into_log->metal_detector_measure_id = $update_metal_measures->id;

                $add_new_record_into_log->md_model_result_old = $update_metal_measures->md_model_result;
                $add_new_record_into_log->md_model_result_new = $request->md_model_result;

                $add_new_record_into_log->mm_2_fe_old = $update_metal_measures->mm_2_fe;
                $add_new_record_into_log->mm_2_fe_new = $request->mm_2_fe;

                $add_new_record_into_log->mm_3_nfe_old = $update_metal_measures->mm_3_nfe;
                $add_new_record_into_log->mm_3_nfe_new = $request->mm_3_nfe;

                $add_new_record_into_log->mm_4_ss_old = $update_metal_measures->mm_4_ss;
                $add_new_record_into_log->mm_4_ss_new = $request->mm_4_ss;

                $add_new_record_into_log->confirm_label_old = $update_metal_measures->confirm_label;
                $add_new_record_into_log->confirm_label_new = $request->confirm_label;

                $add_new_record_into_log->comments_old = $update_metal_measures->comments;
                $add_new_record_into_log->comments_new = $request->comments;

                $add_new_record_into_log->initial_old = $update_metal_measures->initial;
                $add_new_record_into_log->initial_new = $request->initial;

                $add_new_record_into_log->created_by = Auth::user()->id;
                $add_new_record_into_log->save();


                $update_metal_measures->daily_measure_id = $daily_measure_id_decrypt;
                $update_metal_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $update_metal_measures->measure_time = $get_details_from_daily_measure->start_time;
                $update_metal_measures->metal_detector_item_id = $blending_item_id_decrypt;
                $update_metal_measures->md_model_result = $request->md_model_result;
                $update_metal_measures->mm_2_fe = $request->mm_2_fe;
                $update_metal_measures->mm_3_nfe = $request->mm_3_nfe;
                $update_metal_measures->mm_4_ss = $request->mm_4_ss;
                $update_metal_measures->confirm_label = $request->confirm_label;
                $update_metal_measures->comments = $request->comments;
                $update_metal_measures->initial = $request->initial;
                $update_metal_measures->status = "Submitted";
                $update_metal_measures->update();

                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('metal_detector_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                }
                return redirect()->route('metal_detector_list', [Crypt::encrypt(7), Crypt::encrypt($request->daily_measure_id)])->with('success', 'Metal Detector process has been Updated successfully.');
            } else {
                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('metal_detector_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                    return redirect()->route('metal_detector_list', [Crypt::encrypt(7), Crypt::encrypt($request->daily_measure_id)])->with('success', 'The item is verified. No new update found!');
                }
                return redirect()->route('metal_detector_list', [Crypt::encrypt(7), Crypt::encrypt($request->daily_measure_id)])->with('warning', 'No update found!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('metal_detector_list', [Crypt::encrypt(7), Crypt::encrypt($request->daily_measure_id)])->with('error', 'Error:' . $th->getMessage());
        }
    }

    private function old_and_new_measure_data($new_data = null, $old_data = null){

        if ($new_data->md_model_result != $old_data->md_model_result) {
            return true;
        }

        if ($new_data->mm_2_fe != $old_data->mm_2_fe) {
            return true;
        }

        if ($new_data->mm_3_nfe != $old_data->mm_3_nfe) {
            return true;
        }

        if ($new_data->mm_4_ss != $old_data->mm_4_ss) {
            return true;
        }

        if ($new_data->confirm_label != $old_data->confirm_label) {
            return true;
        }

        if ($new_data->comments != $old_data->comments) {
            return true;
        }

        if ($new_data->initial != $old_data->initial) {
            return true;
        }
    }

    public function metal_log($mixing_measure_id = null)
    {
        $mixing_measure_id_decrypt = Crypt::decrypt($mixing_measure_id);

        $get_measure_item = MetalDetectorMeasure::join('metal_detector_items', 'metal_detector_items.id', '=', 'metal_detector_measures.metal_detector_item_id')
            ->where('metal_detector_measures.id', $mixing_measure_id_decrypt)
            ->select('metal_detector_items.item_name', 'metal_detector_items.id as item_id', 'metal_detector_measures.daily_measure_id', 'metal_detector_measures.measure_date')
            ->first();
        // dd($get_measure_item);
        $get_all_blending_measure_log = MetalDetectorLog::where('metal_detector_measure_id', $mixing_measure_id_decrypt)
            ->orderBy('created_at', 'DESC')
            ->get();

        $get_route = route('metal_detective_details', [Crypt::encrypt($get_measure_item->item_id), Crypt::encrypt($get_measure_item->daily_measure_id)]);
        return view('metal_detector_measures.metal_log', compact('get_route', 'get_measure_item', 'get_all_blending_measure_log'));
    }

    //Download metal detector measure excel
    public function download_metal_detector_excel($daily_measure_id)
    {
        $measure_date = DailyMeasure::where('id', $daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));
        return Excel::download(new MetalDetectorMeasuresExport($daily_measure_id), 'metal_detector_measures_' . $formatted_date . '.xlsx');
    }

    public function metal_detector_add_items()
    {
        $get_route = route('item_list', Crypt::encrypt(7));
        return view('item-master.metal-detector-items.add-metal-detector-item', compact('get_route'));
    }

    public function add_metal_detector_item_action(Request $request)
    {
        $exists = MetalDetectorItem::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $add_metal_detector_item = new MetalDetectorItem();
        $add_metal_detector_item->item_name = $request->item_name;
        $add_metal_detector_item->weight = $request->weight;
        $add_metal_detector_item->item_unit = $request->item_unit;
        $add_metal_detector_item->save();

        return redirect()->route('item_list', Crypt::encrypt(7))->with('success', 'Item added successfully!');
    }

    public function metal_detector_edit_items($id)
    {
        $decrypt_item_id = Crypt::decrypt($id);
        $get_item = MetalDetectorItem::where('id', $decrypt_item_id)->first();
        $get_route = route('item_list', Crypt::encrypt(7));
        return view('item-master.metal-detector-items.edit-metal-detector-item', compact('get_route', 'get_item'));
    }

    public function edit_metal_detector_item_action(Request $request, $id)
    {
        $exists = MetalDetectorItem::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $edit_metal_detector_item = MetalDetectorItem::find($id);
        $edit_metal_detector_item->item_name = $request->item_name;
        $edit_metal_detector_item->weight = $request->weight;
        $edit_metal_detector_item->item_unit = $request->item_unit;
        $edit_metal_detector_item->status = $request->status;
        $edit_metal_detector_item->save();

        return redirect()->route('item_list', Crypt::encrypt(7))->with('success', 'Item updated successfully!');
    }

    public function download_metal_detector_pdf($daily_measure_id)
    {
        $decrypt_daily_measure_id = Crypt::decrypt($daily_measure_id);
        $measure_date = DailyMeasure::where('id', $decrypt_daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));

        $metal_detector_measures = MetalDetectorMeasure::join('metal_detector_items', 'metal_detector_items.id', '=', 'metal_detector_measures.metal_detector_item_id')
            ->join('users', 'users.id', '=', 'metal_detector_measures.created_by')
            ->where('metal_detector_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->where('metal_detector_measures.status', 'Verified')
            ->select([
                'metal_detector_items.item_name',
                'metal_detector_items.weight',
                'metal_detector_measures.*',
            ])
            ->orderBy('metal_detector_measures.id', 'ASC')
            ->get();

            $get_created_and_review_details = DB::table('metal_detector_measures')
                ->where('metal_detector_measures.daily_measure_id', $decrypt_daily_measure_id)
                ->leftJoin('users as created_user', 'created_user.id', '=', 'metal_detector_measures.created_by')
                ->leftJoin('users as review_user', 'review_user.id', '=', 'metal_detector_measures.reviewed_by')
                ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'metal_detector_measures.created_at', 'metal_detector_measures.reviewed_at')
                ->get();
            $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
            $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

            $get_created_and_review_data = [
                'created_by_name' => $created_by_names,
                'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)):'',
                'review_by_name' => $review_by_names,
                'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)):'',
            ];

        return Pdf::loadView('metal_detector_measures.metal_detector_measure_pdf', compact('get_created_and_review_data', 'metal_detector_measures', 'measure_date'))
            ->stream('metal_detector_measures_' . $formatted_date . '.pdf');
    }
}
