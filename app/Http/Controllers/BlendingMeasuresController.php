<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Exports\BlendingMeasuresExport;
use App\Models\BlendingItems;
use App\Models\BlendingLog;
use App\Models\BlendingMeasure;
use App\Models\DailyMeasure;
use App\Models\Measure_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class BlendingMeasuresController extends Controller
{

    public function blending_list($category_id = null, $date_id = null)
    {
        $category_id_dec = Crypt::decrypt($category_id);
        $date_id_dec = Crypt::decrypt($date_id);
        $daily_measures = Measure_category::where('id', $category_id_dec)->first();
        $get_the_date_details = DailyMeasure::where('id', $date_id_dec)->first();

        $get_the_blending_items = DB::table('blending_items')
            ->leftJoin('blending_measures', function ($join) use ($date_id_dec) {
                $join->on('blending_measures.blending_item_id', '=', 'blending_items.id')
                    ->where('blending_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->leftJoin('users as technician', 'technician.id', '=', 'blending_measures.created_by')
            ->leftJoin('users as manager', 'manager.id', '=', 'blending_measures.reviewed_by')
            ->select(
                'blending_items.item_name',
                'blending_items.weight',
                'blending_items.id as blending_id',
                'blending_measures.status',
                'blending_measures.batch_no',
                'technician.name as technician_name',
                'manager.name as manager_name'
            )
            ->where('blending_items.status', 'Active')
            ->orderBy('blending_items.item_name', 'ASC')
            ->orderBy('blending_measures.batch_no', 'ASC')
            ->get();

        // $all_blending_item_count = DB::table('blending_items')
        //     // ->leftJoin('blending_measures', 'blending_measures.blending_item_id', '=', 'blending_items.id')
        //     ->where('blending_items.status', 'Inactive')
        //     ->leftJoin('blending_measures', function ($join) use ($date_id_dec) {
        //         $join->on('blending_measures.blending_item_id', '=', 'blending_items.id')
        //             ->where('blending_measures.daily_measure_id', '=', $date_id_dec);
        //     })
        //     ->selectRaw('COUNT(blending_items.id) as total_items, SUM(CASE WHEN blending_measures.status IN ("Submitted", "Verified") THEN 1 ELSE 0 END) as completed_status')
        //     ->first();

        $all_blending_item_submitted_count = BlendingMeasure::where('daily_measure_id', $date_id_dec)->where('status', 'Submitted')->count();
        $all_blending_item_verify_count = BlendingMeasure::where('daily_measure_id', $date_id_dec)->where('status', 'Verified')->count();
        // dd($all_blending_item_count);
        $get_route = route('measurement_category', [$date_id]);
        return view('blending_measures.index', compact('all_blending_item_submitted_count', 'get_route', 'category_id', 'daily_measures', 'get_the_blending_items', 'get_the_date_details', 'all_blending_item_verify_count', 'date_id'));
    }

    public function blending_details($item_id = null, $date_id = null, $batch_no = null)
    {
        $item_id_decrypt = Crypt::decrypt($item_id);
        $date_id_decrypt = Crypt::decrypt($date_id);

        $get_the_measure_date = DailyMeasure::where('id', $date_id_decrypt)->select('measure_date', 'id')->first();
        $get_the_item_details = BlendingItems::where('id', $item_id_decrypt)->first();

        // find blending_measures record
        $find_blending_measure_by_id = BlendingMeasure::where('blending_item_id', $item_id_decrypt)
            ->where('daily_measure_id', $get_the_measure_date->id)
            ->where('batch_no', $batch_no)
            ->first();
        $get_route = route('blending_list', [Crypt::encrypt(5), $date_id]);
        if ($find_blending_measure_by_id) {
            return view('blending_measures.edit', compact('batch_no', 'get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date', 'find_blending_measure_by_id'));
        } else {
            return view('blending_measures.blending_details', compact('batch_no', 'get_route', 'date_id', 'item_id', 'get_the_item_details', 'get_the_measure_date'));
        }
    }

    public function blending_details_action(Request $request)
    {
        try {
            $daily_measure_id_decrypt = Crypt::decrypt($request->blending_measure_id);
            $blending_item_id_decrypt = Crypt::decrypt($request->blending_item_id);

            $get_blending_item_details = BlendingItems::where('id', $blending_item_id_decrypt)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $add_blending_measures = new BlendingMeasure();
            $add_blending_measures->daily_measure_id = $daily_measure_id_decrypt;
            $add_blending_measures->blending_item_id = $blending_item_id_decrypt;
            $add_blending_measures->batch_no = $request->batch_no;
            $add_blending_measures->measure_date = $get_details_from_daily_measure->measure_date;
            $add_blending_measures->measure_time = $get_details_from_daily_measure->start_time;
            $add_blending_measures->ph_result = $request->ph_result;
            $add_blending_measures->temperature = $request->temperature;
            $add_blending_measures->appearance = $request->appearance;
            $add_blending_measures->odor = $request->odor;
            $add_blending_measures->taste = $request->taste;
            $add_blending_measures->comments = $request->comments;
            $add_blending_measures->initial = $request->initial;
            $add_blending_measures->status = "Submitted";
            $add_blending_measures->created_by = Auth::user()->id;
            $add_blending_measures->save();

            if ($request->ph_result < $get_blending_item_details->ph_min  || $request->ph_result > $get_blending_item_details->ph_max) {
                return redirect()->route('blending_list', [Crypt::encrypt(5), $request->blending_measure_id])->with('value_over_rate', 'Test Failed! Place the blending on hold and add extra lime juice 0.1lb at a time until the test passes.');
            }

            return redirect()->route('blending_list', [Crypt::encrypt(5), $request->blending_measure_id])->with('success', 'Blending process has been added successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('blending_list', [Crypt::encrypt(5), $request->blending_measure_id])->with('error', 'Error:' . $th->getMessage());
        }
    }

    public function blending_details_update_action(Request $request)
    {
        try {
            $daily_measure_id_decrypt = $request->blending_measure_id;
            $blending_item_id_decrypt = $request->blending_item_id;

            $get_blending_item_details = BlendingItems::where('id', $blending_item_id_decrypt)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $update_blending_measures = BlendingMeasure::find($request->id);
            // dd($update_blending_measures);

            if ($this->old_and_new_measure_data($request, $update_blending_measures)) {
                $add_new_record_into_log = new BlendingLog();
                $add_new_record_into_log->daily_measure_id = $daily_measure_id_decrypt;
                $add_new_record_into_log->measure_date = $get_details_from_daily_measure->measure_date;
                $add_new_record_into_log->measure_time = $get_details_from_daily_measure->start_time;
                $add_new_record_into_log->blending_item_id = $blending_item_id_decrypt;
                $add_new_record_into_log->blending_measure_id = $update_blending_measures->id;
                $add_new_record_into_log->ph_result_old = $update_blending_measures->ph_result;
                $add_new_record_into_log->ph_result_new = $request->ph_result;
                $add_new_record_into_log->temperature_old = $update_blending_measures->temperature;
                $add_new_record_into_log->temperature_new = $request->temperature;
                $add_new_record_into_log->appearance_old = $update_blending_measures->appearance;
                $add_new_record_into_log->appearance_new = $request->appearance;
                $add_new_record_into_log->odor_new = $request->odor;
                $add_new_record_into_log->odor_old = $update_blending_measures->odor;
                $add_new_record_into_log->taste_old = $update_blending_measures->taste;
                $add_new_record_into_log->taste_new = $request->taste;
                $add_new_record_into_log->comments_old = $update_blending_measures->comments;
                $add_new_record_into_log->comments_new = $request->comments;
                $add_new_record_into_log->initial_old = $update_blending_measures->initial;
                $add_new_record_into_log->initial_new = $request->initial;
                $add_new_record_into_log->created_by = Auth::user()->id;
                $add_new_record_into_log->save();

                $update_blending_measures->daily_measure_id = $daily_measure_id_decrypt;
                $update_blending_measures->blending_item_id = $blending_item_id_decrypt;
                $update_blending_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $update_blending_measures->measure_time = $get_details_from_daily_measure->start_time;
                $update_blending_measures->ph_result = $request->ph_result;
                $update_blending_measures->temperature = $request->temperature;
                $update_blending_measures->appearance = $request->appearance;
                $update_blending_measures->odor = $request->odor;
                $update_blending_measures->taste = $request->taste;
                $update_blending_measures->comments = $request->comments;
                $update_blending_measures->initial = $request->initial;
                $update_blending_measures->status = "Submitted";
                $update_blending_measures->update();

                if ($request->ph_result < $get_blending_item_details->ph_min  || $request->ph_result > $get_blending_item_details->ph_max) {
                    return redirect()->route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($request->blending_measure_id)])->with('value_over_rate', 'Test Failed! Place the blending on hold and add extra lime juice 0.1lb at a time until the test passes.');
                }

                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('blending_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                }

                return redirect()->route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($request->blending_measure_id)])->with('success', 'Blending process has been Updated successfully.');
            } else {
                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('blending_measures', $request->id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                    return redirect()->route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($request->blending_measure_id)])->with('success', 'The item is verified. No new update found!');
                }
                return redirect()->route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($request->blending_measure_id)])->with('warning', 'No update found!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($request->blending_measure_id)])->with('error', 'Error:' . $th->getMessage());
        }
    }

    private function old_and_new_measure_data($new_data = null, $old_data = null)
    {
        if ($new_data->ph_result != $old_data->ph_result) {
            return true;
        }

        if ($new_data->temperature != $old_data->temperature) {
            return true;
        }

        if ($new_data->appearance != $old_data->appearance) {
            return true;
        }

        if ($new_data->odor != $old_data->odor) {
            return true;
        }

        if ($new_data->taste != $old_data->taste) {
            return true;
        }

        if ($new_data->comments != $old_data->comments) {
            return true;
        }

        if ($new_data->initial != $old_data->initial) {
            return true;
        }
    }

    public function log_list($blending_measure_id = null)
    {
        $blending_measure_id_decrypt = Crypt::decrypt($blending_measure_id);
        $get_measure_item = BlendingMeasure::join('blending_items', 'blending_items.id', '=', 'blending_measures.blending_item_id')
            ->where('blending_measures.id', $blending_measure_id_decrypt)
            ->select('blending_items.item_name', 'blending_items.id as item_id', 'blending_measures.daily_measure_id', 'blending_measures.measure_date', 'blending_measures.batch_no')
            ->first();
        $get_all_blending_measure_log = BlendingLog::where('blending_measure_id', $blending_measure_id_decrypt)
            ->orderBy('created_at', 'DESC')
            ->get();

        $get_route = route('blending_details', [Crypt::encrypt($get_measure_item->item_id), Crypt::encrypt($get_measure_item->daily_measure_id), $get_measure_item->batch_no]);
        return view('blending_measures.log_list', compact('get_route', 'get_measure_item', 'get_all_blending_measure_log'));
    }

    //Download blending measure excel
    public function download_blending_excel($daily_measure_id)
    {
        $measure_date = DailyMeasure::where('id', $daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));
        return Excel::download(new BlendingMeasuresExport($daily_measure_id), 'blending_measures_' . $formatted_date . '.xlsx');
    }

    public function add_blending_item()
    {
        $get_route = route('item_list', Crypt::encrypt(5));
        return view('item-master.blending-items.add-blending-item', compact('get_route'));
    }

    public function add_blending_item_action(Request $request)
    {
        $exists = BlendingItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name already exists!');
        }

        $add_blending_item = new BlendingItems();
        $add_blending_item->item_name = $request->item_name;
        $add_blending_item->weight = $request->weight;
        $add_blending_item->ph_min = $request->ph_min;
        $add_blending_item->ph_max = $request->ph_max;
        $add_blending_item->temperature = $request->temperature;
        $add_blending_item->save();

        return redirect()->route('item_list', Crypt::encrypt(5))->with('success', 'Item added successfully!');
    }

    public function blending_edit_items($id)
    {
        $decrypt_item_id = Crypt::decrypt($id);
        $get_item = BlendingItems::where('id', $decrypt_item_id)->first();
        $get_route = route('item_list', Crypt::encrypt(5));
        return view('item-master.blending-items.edit-blending-item', compact('get_route', 'get_item'));
    }

    public function edit_blending_item_action(Request $request, $id)
    {
        $exists = BlendingItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name already exists!');
        }

        $edit_blending_item = BlendingItems::find($id);
        $edit_blending_item->item_name = $request->item_name;
        $edit_blending_item->weight = $request->weight;
        $edit_blending_item->ph_min = $request->ph_min;
        $edit_blending_item->ph_max = $request->ph_max;
        $edit_blending_item->temperature = $request->temperature;
        $edit_blending_item->status = $request->status;
        $edit_blending_item->save();

        return redirect()->route('item_list', Crypt::encrypt(5))->with('success', 'Item updated successfully!');
    }

    //Download blending measuer pdf
    public function download_blending_pdf($daily_measure_id)
    {
        $decrypt_daily_measure_id = Crypt::decrypt($daily_measure_id);
        $measure_date = DailyMeasure::where('id', $decrypt_daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));

        $blending_measures = BlendingMeasure::join('blending_items', 'blending_items.id', '=', 'blending_measures.blending_item_id')
            ->join('users', 'users.id', '=', 'blending_measures.created_by')
            ->where('blending_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->where('blending_measures.status', 'Verified')
            ->select(
                'blending_items.item_name',
                'blending_measures.batch_no',
                'blending_measures.ph_result',
                'blending_measures.temperature',
                'blending_measures.appearance',
                'blending_measures.odor',
                'blending_measures.taste',
                'blending_measures.comments',
                'blending_measures.initial',
                'users.name as created_by'
            )
            ->orderBy('blending_measures.id')
            ->orderBy('blending_measures.batch_no')
            ->get();

        $grouped_measures = [];
        foreach ($blending_measures as $measure) {
            if (!isset($grouped_measures[$measure->item_name])) {
                $grouped_measures[$measure->item_name] = [];
            }
            $grouped_measures[$measure->item_name][] = $measure;
        }


        $get_created_and_review_details = DB::table('blending_measures')
            ->where('blending_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'blending_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'blending_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'blending_measures.created_at', 'blending_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)) : '',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)) : '',
        ];

        return Pdf::loadView('blending_measures.blending_measure_pdf', compact('get_created_and_review_data', 'grouped_measures', 'measure_date'))
            ->stream('blending_measures_' . $formatted_date . '.pdf');
    }

    public function get_item_batch_no($daily_measure_id = null, $item_id = null)
    {
        $daily_measure_id_decode = Crypt::decrypt($daily_measure_id);
        $item_id_decode = Crypt::decrypt($item_id);
        $data = get_all_batch_it_by_item_id_and_daily_measure_id($daily_measure_id_decode, $item_id_decode);
        return response()->json([
            'data' => $data
        ]);
    }

    public function delete_blending_measure($id = null)
    {
        DB::beginTransaction();
        try {
            $blendingMeasure = BlendingMeasure::findOrFail($id);
            $daily_measure_id = $blendingMeasure->daily_measure_id;

            BlendingLog::where('blending_measure_id', $id)->delete();
            $blendingMeasure->delete();
            DB::commit();
            return redirect()
                ->route('measurement_category', Crypt::encrypt($daily_measure_id))
                ->with('success', 'The measure has been deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Measure Delete Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'The measure was not deleted.');
        }
    }
}
