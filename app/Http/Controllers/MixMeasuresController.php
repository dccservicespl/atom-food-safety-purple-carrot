<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Exports\MixingMeasuresExport;
use App\Models\DailyMeasure;
use App\Models\Measure_category;
use App\Models\MixingItems;
use App\Models\MixingMeasure;
use App\Models\MixLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MixMeasuresController extends Controller
{
    public function mix_list($category_id = null, $date_id = null)
    {
        $category_id_dec = Crypt::decrypt($category_id);
        $date_id_dec = Crypt::decrypt($date_id);
        $daily_measures = Measure_category::where('id', $category_id_dec)->first();
        $get_the_date_details = DailyMeasure::where('id', $date_id_dec)->first();

        $get_the_blending_items = DB::table('mixing_items')
            ->leftJoin('mixing_measures', function ($join) use ($date_id_dec) {
                $join->on('mixing_measures.mixing_item_id', '=', 'mixing_items.id')
                    ->where('mixing_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->leftJoin('users as technician', 'technician.id', 'mixing_measures.created_by')
            ->leftJoin('users as manager', 'manager.id', 'mixing_measures.reviewed_by')
            ->select('mixing_items.item_name', 'mixing_items.weight', 'mixing_items.id as blending_id', 'mixing_measures.status', 'technician.name as technician_name', 'manager.name as manager_name')
            ->where('mixing_items.status', 'Active')
            ->get();

        $all_blending_item_count = DB::table('mixing_items')
            // ->leftJoin('blending_measures', 'blending_measures.blending_item_id', '=', 'blending_items.id')
            ->leftJoin('mixing_measures', function ($join) use ($date_id_dec) {
                $join->on('mixing_measures.mixing_item_id', '=', 'mixing_items.id')
                    ->where('mixing_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->selectRaw('COUNT(mixing_items.id) as total_items,
                                                    SUM(CASE WHEN mixing_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as completed_status')
            ->first();
        $get_route = route('measurement_category', [$date_id]);
        return view('mix_measures.index', compact('get_route', 'daily_measures', 'get_the_blending_items', 'get_the_date_details', 'all_blending_item_count', 'date_id'));
    }

    public function blending_details($item_id = null, $date_id = null)
    {
        $item_id_decrypt = Crypt::decrypt($item_id);
        $date_id_decrypt = Crypt::decrypt($date_id);

        $get_the_measure_date = DailyMeasure::where('id', $date_id_decrypt)->select('measure_date', 'id')->first();
        $get_the_item_details = MixingItems::where('id', $item_id_decrypt)->first();

        // find blending_measures record
        $find_blending_measure_by_id = MixingMeasure::where('mixing_item_id', $item_id_decrypt)
            ->where('daily_measure_id', $get_the_measure_date->id)
            ->first();
        $get_route = route('mix_list', [Crypt::encrypt(6), $date_id]);
        if ($find_blending_measure_by_id) {
            return view('mix_measures.mix-measure-edit', compact('get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date', 'find_blending_measure_by_id'));
        } else {
            return view('mix_measures.mix-measor', compact('get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date'));
        }
    }

    public function mix_measure_action(Request $request)
    {
        try {
            // dd($request->all());
            $daily_measure_id_decrypt = Crypt::decrypt($request->mixing_measure_id);
            $mixing_item_id = Crypt::decrypt($request->mixing_item_id);

            $get_blending_item_details = MixingItems::where('id', $mixing_item_id)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $add_blending_measures = new MixingMeasure();
            $add_blending_measures->daily_measure_id = $daily_measure_id_decrypt;
            $add_blending_measures->mixing_item_id = $mixing_item_id;
            $add_blending_measures->measure_date = $get_details_from_daily_measure->measure_date;
            $add_blending_measures->measure_time = $get_details_from_daily_measure->start_time;
            $add_blending_measures->odor = $request->odor;
            $add_blending_measures->appearance = $request->appearance;
            $add_blending_measures->temperature_1 = $request->temperature_1;
            $add_blending_measures->temperature_2 = $request->temperature_2;
            $add_blending_measures->weight_1 = $request->weight_1;
            $add_blending_measures->weight_2 = $request->weight_2;
            $add_blending_measures->weight_3 = $request->weight_3;
            $add_blending_measures->weight_4 = $request->weight_4;
            $add_blending_measures->table_line = $request->table;
            $add_blending_measures->scale = $request->scale;
            $add_blending_measures->comments = $request->comments;
            $add_blending_measures->status = "Submitted";
            $add_blending_measures->created_by = Auth::user()->id;
            $add_blending_measures->save();

            if ($request->temperature_1 > $get_blending_item_details->temperature) {
                return redirect()->route('mix_list', [Crypt::encrypt(6), $request->mixing_measure_id])->with('temperature_over_rate', 'Test Failed! The final packaged product must be maintained at or below 40°F during both storage and processing.');
            }

            return redirect()->route('mix_list', [Crypt::encrypt(6), $request->mixing_measure_id])->with('success', 'Mix Measure process has been added successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('mix_list', [Crypt::encrypt(6), $request->mixing_measure_id])->with('error', 'Error:' . $th->getMessage());
        }
    }

    public function mix_measure_update_action(Request $request)
    {
        try {
            // dd($request->all());
            $daily_measure_id_decrypt = $request->daily_measure_id;
            $mixing_item_id_decrypt = $request->mixing_item_id;

            $get_blending_item_details = MixingItems::where('id', $mixing_item_id_decrypt)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $update_mixing_measures = MixingMeasure::find($request->id);

            if ($this->old_and_new_measure_data($request, $update_mixing_measures)) {
                $add_new_record_into_log = new MixLog();
                $add_new_record_into_log->daily_measure_id = $daily_measure_id_decrypt;
                $add_new_record_into_log->measure_date = $get_details_from_daily_measure->measure_date;
                $add_new_record_into_log->measure_time = $get_details_from_daily_measure->start_time;
                $add_new_record_into_log->mixing_item_id = $mixing_item_id_decrypt;
                $add_new_record_into_log->mix_measure_id = $update_mixing_measures->id;
                $add_new_record_into_log->appearance_old = $update_mixing_measures->appearance;
                $add_new_record_into_log->appearance_new = $request->appearance;
                $add_new_record_into_log->odor_new = $request->odor;
                $add_new_record_into_log->odor_old = $update_mixing_measures->odor;
                $add_new_record_into_log->temperature_1_old = $update_mixing_measures->temperature_1;
                $add_new_record_into_log->temperature_1_new = $request->temperature_1;
                $add_new_record_into_log->temperature_2_old = $update_mixing_measures->temperature_2;
                $add_new_record_into_log->temperature_2_new = $request->temperature_2;
                $add_new_record_into_log->weight_1_old = $update_mixing_measures->weight_1;
                $add_new_record_into_log->weight_1_new = $request->weight_1;
                $add_new_record_into_log->weight_2_old = $update_mixing_measures->weight_2;
                $add_new_record_into_log->weight_2_new = $request->weight_2;
                $add_new_record_into_log->weight_3_old = $update_mixing_measures->weight_3;
                $add_new_record_into_log->weight_3_new = $request->weight_3;
                $add_new_record_into_log->weight_4_old = $update_mixing_measures->weight_4;
                $add_new_record_into_log->weight_4_new = $request->weight_4;
                $add_new_record_into_log->table_line_old = $update_mixing_measures->table_line;
                $add_new_record_into_log->table_line_new = $request->table;
                $add_new_record_into_log->scale_old = $update_mixing_measures->scale;
                $add_new_record_into_log->scale_new = $request->scale;
                $add_new_record_into_log->comments_old = $update_mixing_measures->comments;
                $add_new_record_into_log->comments_new = $request->comments;
                $add_new_record_into_log->created_by = Auth::user()->id;
                $add_new_record_into_log->save();

                $update_mixing_measures->daily_measure_id = $daily_measure_id_decrypt;
                $update_mixing_measures->mixing_item_id = $mixing_item_id_decrypt;
                $update_mixing_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $update_mixing_measures->measure_time = $get_details_from_daily_measure->start_time;
                $update_mixing_measures->odor = $request->odor;
                $update_mixing_measures->appearance = $request->appearance;
                $update_mixing_measures->temperature_1 = $request->temperature_1;
                $update_mixing_measures->temperature_2 = $request->temperature_2;
                $update_mixing_measures->weight_1 = $request->weight_1;
                $update_mixing_measures->weight_2 = $request->weight_2;
                $update_mixing_measures->weight_3 = $request->weight_3;
                $update_mixing_measures->weight_4 = $request->weight_4;
                $update_mixing_measures->table_line = $request->table;
                $update_mixing_measures->scale = $request->scale;
                $update_mixing_measures->comments = $request->comments;
                $update_mixing_measures->status = "Submitted";
                $update_mixing_measures->update();

                if ($request->temperature_1 > $get_blending_item_details->temperature || $request->temperature_2 > $get_blending_item_details->temperature) {
                    return redirect()->route('mix_list', [Crypt::encrypt(6), Crypt::encrypt($request->daily_measure_id)])->with('temperature_over_rate', 'Test Failed! The final packaged product must be maintained at or below 40°F during both storage and processing.');
                }
                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('mixing_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                }
                return redirect()->route('mix_list', [Crypt::encrypt(6), Crypt::encrypt($request->daily_measure_id)])->with('success', 'Mixing process has been Updated successfully.');
            } else {
                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('mixing_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                    return redirect()->route('mix_list', [Crypt::encrypt(6), Crypt::encrypt($request->daily_measure_id)])->with('success', 'The item is verified. No new update found!');
                }
                return redirect()->route('mix_list', [Crypt::encrypt(6), Crypt::encrypt($request->daily_measure_id)])->with('warning', 'No update found!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('mix_list', [Crypt::encrypt(6), Crypt::encrypt($request->daily_measure_id)])->with('error', 'Error:' . $th->getMessage());
        }
    }

    private function old_and_new_measure_data($new_data = null, $old_data = null)
    {
        if ($new_data->odor != $old_data->odor) {
            return true;
        }

        if ($new_data->appearance != $old_data->appearance) {
            return true;
        }

        if ($new_data->temperature_1 != $old_data->temperature_1) {
            return true;
        }

        if ($new_data->temperature_2 != $old_data->temperature_2) {
            return true;
        }

        if ($new_data->weight_1 != $old_data->weight_1) {
            return true;
        }

        if ($new_data->weight_2 != $old_data->weight_2) {
            return true;
        }

        if ($new_data->weight_3 != $old_data->weight_3) {
            return true;
        }

        if ($new_data->weight_4 != $old_data->weight_4) {
            return true;
        }

        if ($new_data->table != $old_data->table_line) {
            return true;
        }

        if ($new_data->scale != $old_data->scale) {
            return true;
        }

        if ($new_data->comments != $old_data->comments) {
            return true;
        }
    }

    public function mix_log($mixing_measure_id = null)
    {
        $mixing_measure_id_decrypt = Crypt::decrypt($mixing_measure_id);

        $get_measure_item = MixingMeasure::join('mixing_items', 'mixing_items.id', '=', 'mixing_measures.mixing_item_id')
            ->where('mixing_measures.id', $mixing_measure_id_decrypt)
            ->select('mixing_items.item_name', 'mixing_items.id as item_id', 'mixing_measures.daily_measure_id', 'mixing_measures.measure_date')
            ->first();
        // dd($get_measure_item);
        $get_all_blending_measure_log = MixLog::where('mix_measure_id', $mixing_measure_id_decrypt)
            ->orderBy('created_at', 'DESC')
            ->get();
        $get_route = route('mixing_details', [Crypt::encrypt($get_measure_item->item_id), Crypt::encrypt($get_measure_item->daily_measure_id)]);
        return view('mix_measures.mix-log', compact('get_route', 'get_measure_item', 'get_all_blending_measure_log'));
    }

    //Download mixing measure excel
    public function download_mix_excel($daily_measure_id)
    {
        $measure_date = DailyMeasure::where('id', $daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));
        return Excel::download(new MixingMeasuresExport($daily_measure_id), 'mixing_measures_' . $formatted_date . '.xlsx');
    }

    public function add_mix_item()
    {
        $get_route = route('item_list', Crypt::encrypt(6));
        return view('item-master.mix-items.add-mix-item', compact('get_route'));
    }

    public function add_mix_item_action(Request $request)
    {
        $exists = MixingItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $add_mix_item = new MixingItems();
        $add_mix_item->item_name = $request->item_name;
        $add_mix_item->ph_min = $request->ph_min;
        $add_mix_item->ph_max = $request->ph_max;
        $add_mix_item->temperature = $request->temperature;
        $add_mix_item->weight = $request->weight;
        $add_mix_item->save();

        return redirect()->route('item_list', Crypt::encrypt(6))->with('success', 'Item added successfully!');
    }

    public function mix_edit_items($id)
    {
        $decrypt_item_id = Crypt::decrypt($id);
        $get_item = MixingItems::where('id', $decrypt_item_id)->first();
        $get_route = route('item_list', Crypt::encrypt(6));
        return view('item-master.mix-items.edit-mix-item', compact('get_route', 'get_item'));
    }

    public function edit_mix_item_action(Request $request, $id)
    {
        $exists = MixingItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $edit_mix_item = MixingItems::find($id);
        $edit_mix_item->item_name = $request->item_name;
        $edit_mix_item->ph_min = $request->ph_min;
        $edit_mix_item->ph_max = $request->ph_max;
        $edit_mix_item->temperature = $request->temperature;
        $edit_mix_item->weight = $request->weight;
        $edit_mix_item->status = $request->status;
        $edit_mix_item->save();

        return redirect()->route('item_list', Crypt::encrypt(6))->with('success', 'Item updated successfully!');
    }

    public function download_mix_measure_pdf($daily_measure_id)
    {
        $decrypt_daily_measure_id = Crypt::decrypt($daily_measure_id);
        $measure_date = DailyMeasure::where('id', $decrypt_daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));

        $mix_measures = MixingMeasure::join('mixing_items', 'mixing_items.id', 'mixing_measures.mixing_item_id')
            ->join('users', 'users.id', 'mixing_measures.created_by')
            ->where('mixing_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->where('mixing_measures.status', 'Verified')
            ->select([
                'mixing_items.item_name',
                'mixing_items.weight',
                'mixing_measures.*'
            ])->orderBy('mixing_measures.id', 'ASC')->get();


        $get_created_and_review_details = DB::table('mixing_measures')
            ->where('mixing_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'mixing_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'mixing_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'mixing_measures.created_at', 'mixing_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)) : '',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)) : '',
        ];

        return Pdf::loadView('mix_measures.mix_measures_pdf', compact('get_created_and_review_data', 'mix_measures', 'measure_date'))
            ->stream('mix_measures_' . $formatted_date . '.pdf');
    }
}
