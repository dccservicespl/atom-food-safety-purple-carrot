<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Exports\GuacamoleMeasuresExport;
use App\Models\DailyMeasure;
use App\Models\GuacamoleItems;
use App\Models\GuacamoleLogs;
use App\Models\GuacamoleMeasure;
use App\Models\Measure_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Gif\Decoder as GifDecoder;
use Kornrunner\ZXing\Decoder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Zxing\QrReader;
use ZbarCode\ZBarCodeReader;
use BaconQrCode\Decoder\Reader;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Intervention\Image\Facades\Image;

class GuacamoleMeasureController extends Controller
{

    public function guacamole_list($category_id = null, $date_id = null)
    {
        $category_id_dec = Crypt::decrypt($category_id);
        $date_id_dec = Crypt::decrypt($date_id);
        $daily_measures = Measure_category::where('id', $category_id_dec)->first();
        $get_the_date_details = DailyMeasure::where('id', $date_id_dec)->first();

        $get_the_guacamole_items = DB::table('guacamole_items')
            ->leftJoin('guacamole_measures', function ($join) use ($date_id_dec) {
                $join->on('guacamole_measures.guacamole_item_id', '=', 'guacamole_items.id')
                    ->where('guacamole_measures.daily_measure_id', '=', $date_id_dec);
            })
            ->leftJoin('users as technician', 'technician.id', 'guacamole_measures.created_by')
            ->leftJoin('users as manager', 'manager.id', 'guacamole_measures.reviewed_by')
            ->where('guacamole_items.status', 'Active')
            ->select('guacamole_items.item_name', 'guacamole_items.weight', 'guacamole_items.id as guacamole_items_id', 'guacamole_measures.status', 'guacamole_measures.batch_no', 'technician.name as technician_name', 'manager.name as manager_name')
            ->orderBy('guacamole_items.id','ASC')
            ->orderBy('guacamole_measures.batch_no', 'ASC')
            ->get();

        $get_route = route('measurement_category', [$date_id]);
        return view('guacamole_measures.index', compact('get_route', 'category_id', 'daily_measures', 'get_the_guacamole_items', 'get_the_date_details', 'date_id'));
    }

    public function guacamole_measure($item_id = null, $daily_measures_id = null, $batch_no = null)
    {
        $item_id_decrypt = Crypt::decrypt($item_id);
        $daily_measures_id_decrypt = Crypt::decrypt($daily_measures_id);
        $get_the_measure_date = DailyMeasure::where('id', $daily_measures_id_decrypt)->select('measure_date', 'id')->first();
        $get_the_item_details = GuacamoleItems::where('id', $item_id_decrypt)->first();

        $find_blending_measure_by_id = GuacamoleMeasure::where('guacamole_item_id', $item_id_decrypt)
            ->where('daily_measure_id', $get_the_measure_date->id)
            ->where('batch_no', $batch_no)
            ->first();

        $get_route = route('guacamole_list', [Crypt::encrypt(8), $daily_measures_id]);
        if ($find_blending_measure_by_id) {
            // dd($find_blending_measure_by_id->item_bar_code);
            return view('guacamole_measures.guacamole-edit', compact('batch_no', 'get_route', 'find_blending_measure_by_id', 'daily_measures_id', 'item_id', 'get_the_item_details', 'get_the_measure_date', 'find_blending_measure_by_id'));
        } else {
            return view('guacamole_measures.guacamole-measor', compact('batch_no', 'get_route', 'find_blending_measure_by_id', 'daily_measures_id', 'item_id', 'get_the_item_details', 'get_the_measure_date'));
        }
    }

    public function guacamole_measure_action(Request $request)
    {
        try {
            $daily_measure_id_decrypt = Crypt::decrypt($request->daily_measures_id);
            $guacamole_item_id = Crypt::decrypt($request->guacamole_item_id);
            $batch_no = $request->batch_no;
            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();

            $check_exist_data = GuacamoleMeasure::where('daily_measure_id', $daily_measure_id_decrypt)
                ->where('guacamole_item_id', $guacamole_item_id)
                ->where('batch_no', $batch_no)
                ->exists();

            if ($check_exist_data) {
                $add_metal_detector_measures = GuacamoleMeasure::where('daily_measure_id', $daily_measure_id_decrypt)->where('guacamole_item_id', $guacamole_item_id)->first();
                $add_metal_detector_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $add_metal_detector_measures->measure_time = $get_details_from_daily_measure->start_time;
                $add_metal_detector_measures->guacamole_item_id = $guacamole_item_id;
                $add_metal_detector_measures->lot_number = $request->lot_number;
                $add_metal_detector_measures->batch_no = $request->batch_no;
                $add_metal_detector_measures->temperature = $request->temperature;
                $add_metal_detector_measures->md_model_result = $request->md_model_result;
                $add_metal_detector_measures->md_fe = $request->fe;
                $add_metal_detector_measures->md_nfe = $request->nfe;
                $add_metal_detector_measures->md_st = $request->st;
                $add_metal_detector_measures->sc_batch_1 = $request->batch_1;
                $add_metal_detector_measures->sc_batch_2 = $request->batch_2;
                $add_metal_detector_measures->sc_batch_3 = $request->batch_3;
                $add_metal_detector_measures->sc_batch_4 = $request->batch_4;
                $add_metal_detector_measures->sc_batch_5 = $request->batch_5;
                $add_metal_detector_measures->sc_batch_6 = $request->batch_6;
                $add_metal_detector_measures->weight_checks_1 = $request->weight_1;
                $add_metal_detector_measures->weight_checks_2 = $request->weight_2;
                $add_metal_detector_measures->weight_checks_3 = $request->weight_3;
                $add_metal_detector_measures->weight_checks_4 = $request->weight_4;
                $add_metal_detector_measures->oxygen_levels_1 = $request->oxygen_levels_1;
                $add_metal_detector_measures->oxygen_levels_2 = $request->oxygen_levels_2;
                $add_metal_detector_measures->oxygen_levels_3 = $request->oxygen_levels_3;
                $add_metal_detector_measures->oxygen_levels_4 = $request->oxygen_levels_4;
                $add_metal_detector_measures->cups = $request->cups;
                $add_metal_detector_measures->lids = $request->lids;

                $add_metal_detector_measures->total_containers = $request->total_containers;
                $add_metal_detector_measures->retains_collected = $request->retains_collected;
                $add_metal_detector_measures->best_by_date = $request->best_by_date;
                $add_metal_detector_measures->comments = $request->comments;
                $add_metal_detector_measures->initial = $request->initial;
                $add_metal_detector_measures->status = "Submitted";
                $add_metal_detector_measures->created_by = Auth::user()->id;
                $add_metal_detector_measures->Update();
            } else {
                $add_metal_detector_measures = new GuacamoleMeasure();
                $add_metal_detector_measures->daily_measure_id = $daily_measure_id_decrypt;
                $add_metal_detector_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $add_metal_detector_measures->measure_time = $get_details_from_daily_measure->start_time;
                $add_metal_detector_measures->guacamole_item_id = $guacamole_item_id;
                $add_metal_detector_measures->lot_number = $request->lot_number;
                $add_metal_detector_measures->batch_no = $request->batch_no;
                $add_metal_detector_measures->temperature = $request->temperature;
                $add_metal_detector_measures->md_model_result = $request->md_model_result;
                $add_metal_detector_measures->md_fe = $request->fe;
                $add_metal_detector_measures->md_nfe = $request->nfe;
                $add_metal_detector_measures->md_st = $request->st;
                $add_metal_detector_measures->sc_batch_1 = $request->batch_1;
                $add_metal_detector_measures->sc_batch_2 = $request->batch_2;
                $add_metal_detector_measures->sc_batch_3 = $request->batch_3;
                $add_metal_detector_measures->sc_batch_4 = $request->batch_4;
                $add_metal_detector_measures->sc_batch_5 = $request->batch_5;
                $add_metal_detector_measures->sc_batch_6 = $request->batch_6;
                $add_metal_detector_measures->weight_checks_1 = $request->weight_1;
                $add_metal_detector_measures->weight_checks_2 = $request->weight_2;
                $add_metal_detector_measures->weight_checks_3 = $request->weight_3;
                $add_metal_detector_measures->weight_checks_4 = $request->weight_4;
                $add_metal_detector_measures->oxygen_levels_1 = $request->oxygen_levels_1;
                $add_metal_detector_measures->oxygen_levels_2 = $request->oxygen_levels_2;
                $add_metal_detector_measures->oxygen_levels_3 = $request->oxygen_levels_3;
                $add_metal_detector_measures->oxygen_levels_4 = $request->oxygen_levels_4;
                $add_metal_detector_measures->cups = $request->cups;
                $add_metal_detector_measures->lids = $request->lids;
                $add_metal_detector_measures->total_containers = $request->total_containers;
                $add_metal_detector_measures->retains_collected = $request->retains_collected;
                $add_metal_detector_measures->best_by_date = $request->best_by_date;
                $add_metal_detector_measures->comments = $request->comments;
                $add_metal_detector_measures->initial = $request->initial;
                $add_metal_detector_measures->status = "Submitted";
                $add_metal_detector_measures->created_by = Auth::user()->id;
                $add_metal_detector_measures->save();
            }

            return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('success', 'Guacamole Measure process has been added successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('error', 'Error:' . $th->getMessage());
        }
    }

    public function guacamole_measure_update_action(Request $request)
    {
        try {
            $daily_measure_id_decrypt = Crypt::decrypt($request->daily_measures_id);
            $blending_item_id_decrypt = Crypt::decrypt($request->guacamole_item_id);

            // $get_blending_item_details = MetalDetectorItem::where('id', $blending_item_id_decrypt)->first();

            $get_details_from_daily_measure = DailyMeasure::where('id', $daily_measure_id_decrypt)->select('measure_date', 'start_time')->first();
            $add_metal_detector_measures = GuacamoleMeasure::find($request->guacamole_measure_id);
            // dd($request->all(), $add_metal_detector_measures);

            if ($this->old_and_new_measure_data($request, $add_metal_detector_measures)) {
                $add_new_record_into_log = new GuacamoleLogs();
                $add_new_record_into_log->daily_measure_id = $daily_measure_id_decrypt;
                $add_new_record_into_log->measure_date = $get_details_from_daily_measure->measure_date;
                $add_new_record_into_log->measure_time = $get_details_from_daily_measure->start_time;
                $add_new_record_into_log->guacamole_item_id = $blending_item_id_decrypt;
                $add_new_record_into_log->guacamole_measure_id = $add_metal_detector_measures->id;
                $add_new_record_into_log->md_model_result_old = $add_metal_detector_measures->md_model_result;
                $add_new_record_into_log->md_model_result_new = $request->md_model_result;
                $add_new_record_into_log->md_fe_old = $add_metal_detector_measures->md_fe;
                $add_new_record_into_log->md_fe_new = $request->fe;
                $add_new_record_into_log->md_nfe_old = $add_metal_detector_measures->md_nfe;
                $add_new_record_into_log->md_nfe_new = $request->nfe;
                $add_new_record_into_log->md_st_old = $add_metal_detector_measures->md_st;
                $add_new_record_into_log->md_st_new = $request->st;
                $add_new_record_into_log->sc_batch_1_old = $add_metal_detector_measures->sc_batch_1;
                $add_new_record_into_log->sc_batch_1_new = $request->batch_1;
                $add_new_record_into_log->sc_batch_2_old = $add_metal_detector_measures->sc_batch_2;
                $add_new_record_into_log->sc_batch_2_new = $request->batch_2;
                $add_new_record_into_log->sc_batch_3_old = $add_metal_detector_measures->sc_batch_3;
                $add_new_record_into_log->sc_batch_3_new = $request->batch_3;
                $add_new_record_into_log->sc_batch_4_old = $add_metal_detector_measures->sc_batch_4;
                $add_new_record_into_log->sc_batch_4_new = $request->batch_4;
                $add_new_record_into_log->sc_batch_5_old = $add_metal_detector_measures->sc_batch_5;
                $add_new_record_into_log->sc_batch_5_new = $request->batch_5;
                $add_new_record_into_log->sc_batch_6_old = $add_metal_detector_measures->sc_batch_6;
                $add_new_record_into_log->sc_batch_6_new = $request->batch_6;
                $add_new_record_into_log->weight_checks_1_old = $add_metal_detector_measures->weight_checks_1;
                $add_new_record_into_log->weight_checks_1_new = $request->weight_1;
                $add_new_record_into_log->weight_checks_2_old = $add_metal_detector_measures->weight_checks_2;
                $add_new_record_into_log->weight_checks_2_new = $request->weight_2;
                $add_new_record_into_log->weight_checks_3_old = $add_metal_detector_measures->weight_checks_3;
                $add_new_record_into_log->weight_checks_3_new = $request->weight_3;
                $add_new_record_into_log->weight_checks_4_old = $add_metal_detector_measures->weight_checks_4;
                $add_new_record_into_log->weight_checks_4_new = $request->weight_4;
                $add_new_record_into_log->oxygen_levels_1_old = $add_metal_detector_measures->oxygen_levels_1;
                $add_new_record_into_log->oxygen_levels_1_new = $request->oxygen_levels_1;
                $add_new_record_into_log->oxygen_levels_2_old = $add_metal_detector_measures->oxygen_levels_2;
                $add_new_record_into_log->oxygen_levels_2_new = $request->oxygen_levels_2;
                $add_new_record_into_log->oxygen_levels_3_old = $add_metal_detector_measures->oxygen_levels_3;
                $add_new_record_into_log->oxygen_levels_3_new = $request->oxygen_levels_3;
                $add_new_record_into_log->oxygen_levels_4_old = $add_metal_detector_measures->oxygen_levels_4;
                $add_new_record_into_log->oxygen_levels_4_new = $request->oxygen_levels_4;

                $add_new_record_into_log->cups_old = $add_metal_detector_measures->cups;
                $add_new_record_into_log->cups_new = $request->cups;

                $add_new_record_into_log->lids_old = $add_metal_detector_measures->lids;
                $add_new_record_into_log->lids_new = $request->lids;

                $add_new_record_into_log->total_containers_old = $add_metal_detector_measures->total_containers;
                $add_new_record_into_log->total_containers_new = $request->total_containers;
                $add_new_record_into_log->retains_collected_old = $add_metal_detector_measures->retains_collected;
                $add_new_record_into_log->retains_collected_new = $request->retains_collected;
                $add_new_record_into_log->best_by_date_old = $add_metal_detector_measures->best_by_date;
                $add_new_record_into_log->best_by_date_new = $request->best_by_date;
                $add_new_record_into_log->comments_old = $add_metal_detector_measures->comments;
                $add_new_record_into_log->comments_new = $request->comments;
                $add_new_record_into_log->initial_old = $add_metal_detector_measures->initial;
                $add_new_record_into_log->initial_new = $request->initial;
                $add_new_record_into_log->created_by = Auth::user()->id;
                $add_new_record_into_log->save();

                $add_metal_detector_measures->daily_measure_id = $daily_measure_id_decrypt;
                $add_metal_detector_measures->measure_date = $get_details_from_daily_measure->measure_date;
                $add_metal_detector_measures->measure_time = $get_details_from_daily_measure->start_time;
                $add_metal_detector_measures->guacamole_item_id = $blending_item_id_decrypt;
                $add_metal_detector_measures->lot_number = $request->lot_number;
                $add_metal_detector_measures->temperature = $request->temperature;
                $add_metal_detector_measures->md_model_result = $request->md_model_result;
                $add_metal_detector_measures->md_fe = $request->fe;
                $add_metal_detector_measures->md_nfe = $request->nfe;
                $add_metal_detector_measures->md_st = $request->st;
                $add_metal_detector_measures->sc_batch_1 = $request->batch_1;
                $add_metal_detector_measures->sc_batch_2 = $request->batch_2;
                $add_metal_detector_measures->sc_batch_3 = $request->batch_3;
                $add_metal_detector_measures->sc_batch_4 = $request->batch_4;
                $add_metal_detector_measures->sc_batch_5 = $request->batch_5;
                $add_metal_detector_measures->sc_batch_6 = $request->batch_6;
                $add_metal_detector_measures->weight_checks_1 = $request->weight_1;
                $add_metal_detector_measures->weight_checks_2 = $request->weight_2;
                $add_metal_detector_measures->weight_checks_3 = $request->weight_3;
                $add_metal_detector_measures->weight_checks_4 = $request->weight_4;
                $add_metal_detector_measures->oxygen_levels_1 = $request->oxygen_levels_1;
                $add_metal_detector_measures->oxygen_levels_2 = $request->oxygen_levels_2;
                $add_metal_detector_measures->oxygen_levels_3 = $request->oxygen_levels_3;
                $add_metal_detector_measures->oxygen_levels_4 = $request->oxygen_levels_4;
                $add_metal_detector_measures->cups = $request->cups;
                $add_metal_detector_measures->lids = $request->lids;
                $add_metal_detector_measures->total_containers = $request->total_containers;
                $add_metal_detector_measures->retains_collected = $request->retains_collected;
                $add_metal_detector_measures->best_by_date = $request->best_by_date;
                $add_metal_detector_measures->comments = $request->comments;
                $add_metal_detector_measures->initial = $request->initial;
                $add_metal_detector_measures->status = "Submitted";
                $add_metal_detector_measures->update();

                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('guacamole_measures', $request->guacamole_measure_id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                }

                return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('success', 'Guacamole Measure process has been Updated successfully.');
            } else {
                if (Auth::user()->role_id == 2) {
                    WebHookHelper::verify_measure_by_id('guacamole_measures', $request->guacamole_measure_id);
                    WebHookHelper::is_all_category_item_completed($daily_measure_id_decrypt);
                    return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('success', 'The item is verified. No new update found!');

                }
                return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('warning', 'No update found!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('guacamole_list', [Crypt::encrypt(8), $request->daily_measures_id])->with('error', 'Error:' . $th->getMessage());
        }
    }

    private function old_and_new_measure_data($new_data = null, $old_data = null)
    {

        if ($new_data->lot_number != $old_data->lot_number) {
            return true;
        }

        if ($new_data->temperature != $old_data->temperature) {
            return true;
        }

        if ($new_data->md_model_result != $old_data->md_model_result) {
            return true;
        }

        if ($new_data->fe != $old_data->md_fe) {
            return true;
        }

        if ($new_data->nfe != $old_data->md_nfe) {
            return true;
        }

        if ($new_data->st != $old_data->md_st) {
            return true;
        }

        if ($new_data->batch_1 != $old_data->sc_batch_1) {
            return true;
        }

        if ($new_data->batch_2 != $old_data->sc_batch_2) {
            return true;
        }

        if ($new_data->batch_3 != $old_data->sc_batch_3) {
            return true;
        }

        if ($new_data->batch_4 != $old_data->sc_batch_4) {
            return true;
        }

        if ($new_data->batch_5 != $old_data->sc_batch_5) {
            return true;
        }

        if ($new_data->batch_6 != $old_data->sc_batch_6) {
            return true;
        }

        if ($new_data->weight_1 != $old_data->weight_checks_1) {
            return true;
        }

        if ($new_data->weight_2 != $old_data->weight_checks_2) {
            return true;
        }

        if ($new_data->weight_3 != $old_data->weight_checks_3) {
            return true;
        }

        if ($new_data->weight_4 != $old_data->weight_checks_4) {
            return true;
        }

        if ($new_data->oxygen_levels_1 != $old_data->oxygen_levels_1) {
            return true;
        }

        if ($new_data->oxygen_levels_2 != $old_data->oxygen_levels_2) {
            return true;
        }

        if ($new_data->oxygen_levels_3 != $old_data->oxygen_levels_3) {
            return true;
        }

        if ($new_data->oxygen_levels_4 != $old_data->oxygen_levels_4) {
            return true;
        }

        if ($new_data->lids != $old_data->lids) {
            return true;
        }

        if ($new_data->cups != $old_data->cups) {
            return true;
        }

        if ($new_data->total_containers != $old_data->total_containers) {
            return true;
        }

        if ($new_data->retains_collected != $old_data->retains_collected) {
            return true;
        }

        if ($new_data->best_by_date != $old_data->best_by_date) {
            return true;
        }

        if ($new_data->comments != $old_data->comments) {
            return true;
        }

        if ($new_data->initial != $old_data->initial) {
            return true;
        }
    }

    public function update_box_bar_code_action(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'guacamole_measure_id' => 'required|exists:guacamole_measures,id'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = '/assets/uploads/box_bar_code/' . $imageName;
                $image->move('assets/uploads/box_bar_code', $imageName);
                $guacamoleMeasure = GuacamoleMeasure::find($request->guacamole_measure_id);
                $guacamoleMeasure->item_bar_code = $imagePath;
                $guacamoleMeasure->save();
                return  redirect()->back()->with('success', 'Box Bar Code Image uploaded successfully!');
            }

            return redirect()->back()->with('error', 'Box Bar Code Image upload failed.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Box Bar Code Image upload failed.' . $th->getMessage());
        }
    }

    public function guacamole_log($mixing_measure_id = null)
    {
        $mixing_measure_id_decrypt = Crypt::decrypt($mixing_measure_id);
        $get_measure_item = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', '=', 'guacamole_measures.guacamole_item_id')
            ->where('guacamole_measures.id', $mixing_measure_id_decrypt)
            ->select('guacamole_items.item_name', 'guacamole_items.id as item_id', 'guacamole_measures.daily_measure_id',  'guacamole_measures.measure_date', 'guacamole_measures.batch_no')
            ->first();

        $get_all_blending_measure_log = GuacamoleLogs::where('guacamole_measure_id', $mixing_measure_id_decrypt)
            ->orderBy('created_at', 'DESC')
            ->get();

        $get_route = route('guacamole_measure', [Crypt::encrypt($get_measure_item->item_id), Crypt::encrypt($get_measure_item->daily_measure_id), $get_measure_item->batch_no??1]);
        return view('guacamole_measures.guacamole_log', compact('get_route', 'get_measure_item', 'get_all_blending_measure_log'));
    }

    public function download_guacamole_excel($daily_measure_id = null)
    {
        $measure_date = DailyMeasure::where('id', $daily_measure_id)->select('measure_date')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));
        return Excel::download(new GuacamoleMeasuresExport($daily_measure_id), 'guacamole_measures_' . $formatted_date . '.xlsx');
    }

    public function read_bar_code(Request $request)
    {
        $guacamole_item_id = Crypt::decrypt($request->guacamole_item_id);
        $daily_measures_id = Crypt::decrypt($request->daily_measures_id);
        $batch_no = $request->batch_no;
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = ('/assets/uploads/item_bar_code/' . $imageName);
                $image->move(('assets/uploads/item_bar_code'), $imageName);

                $add_metal_detector_measures = GuacamoleMeasure::where('daily_measure_id', $daily_measures_id)
                    ->where('guacamole_item_id', $guacamole_item_id)
                    ->where('batch_no', $batch_no)
                    ->first();

                if ($add_metal_detector_measures) {
                    $add_metal_detector_measures->measure_date = $add_metal_detector_measures->measure_date;
                    $add_metal_detector_measures->measure_time = $add_metal_detector_measures->start_time;
                    $add_metal_detector_measures->item_bar_code = $imagePath;
                    $add_metal_detector_measures->daily_measure_id = $daily_measures_id;
                    $add_metal_detector_measures->guacamole_item_id = $guacamole_item_id;
                    $add_metal_detector_measures->created_by = Auth::user()->id;
                    $add_metal_detector_measures->update();
                } else {
                    $add_metal_detector_measures = new GuacamoleMeasure();
                    $add_metal_detector_measures->item_bar_code = $imagePath;
                    $add_metal_detector_measures->daily_measure_id = $daily_measures_id;
                    $add_metal_detector_measures->guacamole_item_id = $guacamole_item_id;
                    $add_metal_detector_measures->created_by = Auth::user()->id;
                    $add_metal_detector_measures->save();
                }
                // dd($add_metal_detector_measures);

                // $lot_number = WebHookHelper::read_lot_no_from_image($imagePath);
                // if ($lot_number === 'Please scan again.') {
                //     $response = 200;
                //     $message = 'Error fatching message';
                //     session()->flash('error', 'Lot number not found. Please scan the bar code again.');
                // }else{
                //     $response = 201;
                //     $message = 'Success!';
                // }
                // return response()->json([
                //     'response' => $response,
                //     'lot_number' => $lot_number,
                //     'message' => $message
                // ]);

                $img_path = $add_metal_detector_measures->item_bar_code ?? '/assets/img/img_icon.png';
                return response()->json([
                    'response' => 201,
                    'lot_number' => '',
                    'img_path' => $img_path,
                    'message' => 'Success'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Error While Upload Barcode:: =>" . $th->getMessage());
            return response()->json([
                'response' => 500,
                'lot_number' => 'Please scan again.',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function store_container_br_code_action(Request $request)
    {
        $find_daily_measure = DailyMeasure::find($request->daily_measure_id);
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $find_daily_measure->measure_date . "_" . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = '/assets/uploads/container_br_code/' . $imageName;
                $image->move('assets/uploads/container_br_code', $imageName);
                $find_daily_measure->container_bar_code_img = $imagePath;
                $find_daily_measure->update();
                return redirect()->back()->with('success', 'You have successfully upload the container barcode.');
            } else {
                return redirect()->back()->with('primary', 'Fail! Please select or click a proper image and upload.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Fail! Something went wrong please try again.');
        }
    }

    public function remove_bar_code($daily_measure_id = null)
    {
        try {
            $find_daily_measure = DailyMeasure::where('id', $daily_measure_id)->update(['container_bar_code_img' => '']);
            return redirect()->back()->with('success', 'You have successfully remove the container barcode.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Fail! Something went wrong please try again.');
        }
    }

    public function guacamole_add_items()
    {
        $get_route = route('item_list', Crypt::encrypt(8));
        return view('item-master.guacamole-items.add-guacamole-item', compact('get_route'));
    }

    public function add_guacamole_item_action(Request $request)
    {
        $exists = GuacamoleItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $add_guacamole_item = new GuacamoleItems();
        $add_guacamole_item->item_name = $request->item_name;
        $add_guacamole_item->weight = $request->weight;
        $add_guacamole_item->save();

        return redirect()->route('item_list', Crypt::encrypt(8))->with('success', 'Item added successfully!');
    }

    public function guacamole_edit_items($id)
    {
        $decrypt_item_id = Crypt::decrypt($id);
        $get_item = GuacamoleItems::where('id', $decrypt_item_id)->first();
        $get_route = route('item_list', Crypt::encrypt(8));
        return view('item-master.guacamole-items.edit-guacamole-item', compact('get_route', 'get_item'));
    }

    public function edit_guacamole_item_action(Request $request, $id)
    {
        $exists = GuacamoleItems::where('item_name', $request->item_name)
            ->where('weight', $request->weight)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Item with same name and weight already exists!');
        }

        $edit_guacamole_item = GuacamoleItems::find($id);
        $edit_guacamole_item->item_name = $request->item_name;
        $edit_guacamole_item->weight = $request->weight;
        $edit_guacamole_item->status = $request->status;
        $edit_guacamole_item->save();

        return redirect()->route('item_list', Crypt::encrypt(8))->with('success', 'Item updated successfully!');
    }

    public function download_guacamole_pdf($decrypt_daily_measure_id = null){

        $measure_date = DailyMeasure::where('id', $decrypt_daily_measure_id)->select('measure_date', 'guacamole_operator_name')->first();
        $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));

        $guacamole_measures = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', 'guacamole_measures.guacamole_item_id')
            ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->where('guacamole_measures.status', 'Verified')
            ->select([
                'guacamole_items.item_name',
                'guacamole_items.weight',
                'guacamole_measures.*'
            ])->orderBy('guacamole_measures.id')
            ->orderBy('guacamole_measures.batch_no')
            ->orderBy('guacamole_items.weight')
            ->get();


            $grouped_measures = [];
            foreach ($guacamole_measures as $measure) {
                $group_key = $measure->item_name . ' (' . $measure->weight . ')';
                if (!isset($grouped_measures[$group_key])) {
                    $grouped_measures[$group_key] = [];
                }
                $grouped_measures[$group_key][] = $measure;
            }

        $guacamole_item_measures = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', 'guacamole_measures.guacamole_item_id')
            ->join('users', 'users.id', 'guacamole_measures.created_by')
            ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->where('guacamole_measures.status', 'Verified')
            ->groupBy('guacamole_measures.guacamole_item_id')
            ->select([
                'guacamole_items.item_name',
                'guacamole_items.weight',
                DB::raw('MAX(guacamole_measures.temperature) as temperature'),
                DB::raw('MAX(guacamole_measures.batch_no) as batch_no'),
                DB::raw('MAX(guacamole_measures.lot_number) as lot_number'),
                DB::raw('MAX(guacamole_measures.updated_at) as updated_at'),
                DB::raw('MAX(guacamole_measures.total_containers) as total_containers'),
                DB::raw('MAX(guacamole_measures.retains_collected) as retains_collected'),
            ])
            ->orderBy('guacamole_measures.guacamole_item_id')
            ->orderBy('guacamole_items.weight')
            ->get();
            // dd($guacamole_item_measures);

        // Fetch Packaging Lot Numbers
        $packaging_lot_numbers = DB::table('guacamole_measures')
            ->join('guacamole_items', 'guacamole_items.id', '=', 'guacamole_measures.guacamole_item_id')
            ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
            ->groupBy('guacamole_items.weight', 'guacamole_measures.cups', 'guacamole_measures.lids')
            ->selectRaw('
                guacamole_items.weight,
                guacamole_measures.cups,
                guacamole_measures.lids
            ')
            ->get();
            // dd($packaging_lot_numbers);

            $get_created_and_review_details = DB::table('guacamole_measures')
                ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
                ->leftJoin('users as created_user', 'created_user.id', '=', 'guacamole_measures.created_by')
                ->leftJoin('users as review_user', 'review_user.id', '=', 'guacamole_measures.reviewed_by')
                ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'guacamole_measures.created_at', 'guacamole_measures.reviewed_at')
                ->get();
            $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
            $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

            $get_created_and_review_data = [
                'created_by_name' => $created_by_names,
                'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)):'',
                'review_by_name' => $review_by_names,
                'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)):'',
                'daily_measure' => $measure_date,
            ];
        return Pdf::loadView('guacamole_measures.guacamole_measures_pdf', compact('get_created_and_review_data','guacamole_measures', 'grouped_measures', 'packaging_lot_numbers', 'measure_date', 'guacamole_item_measures'))
            ->setPaper('a4', 'landscape')->stream('guacamole_measures_' . $formatted_date . '.pdf');
    }

    public function generate_guac_report(Request $request){
        $decrypt_daily_measure_id = $request->daily_measure_id;

        $get_daily_measure_date = DailyMeasure::find($decrypt_daily_measure_id);
        $get_daily_measure_date->guacamole_operator_name = $request->operator_name;
        $get_daily_measure_date->save();

        if ($request->report_type == 'pdf') {
            return self::download_guacamole_pdf($decrypt_daily_measure_id);
        }elseif ($request->report_type == 'excel') {
            return self::download_guacamole_excel($decrypt_daily_measure_id);
        }else{
            abort(404);
        }
    }

    // public function download_guacamole_pdf($daily_measure_id = null){

    //     $decrypt_daily_measure_id = Crypt::decrypt($daily_measure_id);
    //     $measure_date = DailyMeasure::where('id', $decrypt_daily_measure_id)->select('measure_date')->first();
    //     $formatted_date = date('m_d_Y', strtotime($measure_date->measure_date));

    //     $guacamole_measures = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', 'guacamole_measures.guacamole_item_id')
    //         ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
    //         ->where('guacamole_measures.status', 'Verified')
    //         ->select([
    //             'guacamole_items.item_name',
    //             'guacamole_items.weight',
    //             'guacamole_measures.*'
    //         ])->orderBy('guacamole_measures.id')
    //         ->orderBy('guacamole_measures.batch_no')
    //         ->orderBy('guacamole_items.weight')
    //         ->get();


    //         $grouped_measures = [];
    //         foreach ($guacamole_measures as $measure) {
    //             $group_key = $measure->item_name . ' (' . $measure->weight . ')';
    //             if (!isset($grouped_measures[$group_key])) {
    //                 $grouped_measures[$group_key] = [];
    //             }
    //             $grouped_measures[$group_key][] = $measure;
    //         }

    //     $guacamole_item_measures = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', 'guacamole_measures.guacamole_item_id')
    //         ->join('users', 'users.id', 'guacamole_measures.created_by')
    //         ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
    //         ->where('guacamole_measures.status', 'Verified')
    //         ->groupBy('guacamole_measures.guacamole_item_id')
    //         ->select([
    //             'guacamole_items.item_name',
    //             'guacamole_items.weight',
    //             DB::raw('MAX(guacamole_measures.temperature) as temperature'),
    //             DB::raw('MAX(guacamole_measures.batch_no) as batch_no'),
    //             DB::raw('MAX(guacamole_measures.lot_number) as lot_number'),
    //             DB::raw('MAX(guacamole_measures.updated_at) as updated_at'),
    //             DB::raw('MAX(guacamole_measures.total_containers) as total_containers'),
    //             DB::raw('MAX(guacamole_measures.retains_collected) as retains_collected'),
    //         ])
    //         ->orderBy('guacamole_measures.guacamole_item_id')
    //         ->orderBy('guacamole_items.weight')
    //         ->get();
    //         // dd($guacamole_item_measures);

    //     // Fetch Packaging Lot Numbers
    //     $packaging_lot_numbers = DB::table('guacamole_measures')
    //         ->join('guacamole_items', 'guacamole_items.id', '=', 'guacamole_measures.guacamole_item_id')
    //         ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
    //         ->groupBy('guacamole_items.weight', 'guacamole_measures.cups', 'guacamole_measures.lids')
    //         ->selectRaw('
    //             guacamole_items.weight,
    //             guacamole_measures.cups,
    //             guacamole_measures.lids
    //         ')
    //         ->get();
    //         // dd($packaging_lot_numbers);

    //         $get_created_and_review_details = DB::table('guacamole_measures')
    //             ->where('guacamole_measures.daily_measure_id', $decrypt_daily_measure_id)
    //             ->leftJoin('users as created_user', 'created_user.id', '=', 'guacamole_measures.created_by')
    //             ->leftJoin('users as review_user', 'review_user.id', '=', 'guacamole_measures.reviewed_by')
    //             ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'guacamole_measures.created_at', 'guacamole_measures.reviewed_at')
    //             ->get();
    //         $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
    //         $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

    //         $get_created_and_review_data = [
    //             'created_by_name' => $created_by_names,
    //             'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)):'',
    //             'review_by_name' => $review_by_names,
    //             'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)):'',
    //         ];

    //     return Pdf::loadView('guacamole_measures.guacamole_measures_pdf', compact('get_created_and_review_data','guacamole_measures', 'grouped_measures', 'packaging_lot_numbers', 'measure_date', 'guacamole_item_measures'))
    //         ->setPaper('a4', 'landscape')->stream('guacamole_measures_' . $formatted_date . '.pdf');
    // }

    public function get_guaco_item_batch_no($daily_measure_id = null, $item_id = null)
    {
        $daily_measure_id_decode = Crypt::decrypt($daily_measure_id);
        $item_id_decode = Crypt::decrypt($item_id);

        $data = GuacamoleMeasure::where('daily_measure_id', $daily_measure_id_decode)
            ->where('guacamole_item_id', $item_id_decode)
            ->select('batch_no')
            ->pluck('batch_no');
        return response()->json([
            'data' => $data
        ]);
    }

    public function create_new_guacamole_batch($measurement_id = null){
        $get_measure_details = GuacamoleMeasure::find($measurement_id);

        $generate_new_batch_no = GuacamoleMeasure::where('daily_measure_id', $get_measure_details->daily_measure_id)
                                                ->where('guacamole_item_id', $get_measure_details->guacamole_item_id)
                                                ->count() + 1;
        $add_new_record = new GuacamoleMeasure();
        $add_new_record->daily_measure_id = $get_measure_details->daily_measure_id;
        $add_new_record->measure_date = $get_measure_details->measure_date;
        $add_new_record->measure_time = $get_measure_details->measure_time;
        $add_new_record->guacamole_item_id = $get_measure_details->guacamole_item_id;
        $add_new_record->created_by = Auth::user()->id;
        $add_new_record->batch_no = $generate_new_batch_no;
        $add_new_record->save();
        return redirect()->route('guacamole_measure', [Crypt::encrypt($get_measure_details->guacamole_item_id), Crypt::encrypt($get_measure_details->daily_measure_id), $generate_new_batch_no]);
    }


    public function delete_guacamole_measure($id = null){
        DB::beginTransaction();
        try {
            $blendingMeasure = GuacamoleMeasure::findOrFail($id);
            $daily_measure_id = $blendingMeasure->daily_measure_id;

            GuacamoleLogs::where('guacamole_measure_id', $id)->delete();
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
