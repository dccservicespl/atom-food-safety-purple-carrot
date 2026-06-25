<?php
namespace App\Helpers;

use App\Models\BlendingMeasure;
use App\Models\DailyMeasure;
use App\Models\MetalDetectorMeasure;
use App\Models\MixingMeasure;
use App\Models\Inspectiondetails;
use App\Models\InspectionStatus;
use App\Models\UserRoleMaping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebHookHelper{
    public static function verify_measure_by_id($table_name = NULL, $table_id = NULL){
        try {
            DB::table($table_name)->where('id', $table_id)->update(['status' => 'Verified', 'reviewed_by' => Auth::user()->id, 'reviewed_at' => date('Y-m-d h:i:s')]);
            return true;
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }

    }

    // public static function is_all_category_item_completed($daily_measures_id = NULL){
    //     $guacamole_items_status_check = DB::table('guacamole_items')
    //                                         ->leftJoin('guacamole_measures', function ($join) use ($daily_measures_id) {
    //                                             $join->on('guacamole_measures.guacamole_item_id', '=', 'guacamole_items.id')
    //                                                 ->where('guacamole_measures.daily_measure_id', '=', $daily_measures_id);
    //                                         })
    //                                         ->where('guacamole_items.status', 'Active')
    //                                         ->selectRaw('
    //                                             COUNT(guacamole_items.id) as total_items,
    //                                             SUM(CASE WHEN guacamole_measures.status IN ("Verified") THEN 1 ELSE 0 END) as submitted_items')
    //                                         ->first();
    //                                         dd($guacamole_items_status_check);
    //     if ($guacamole_items_status_check->total_items != $guacamole_items_status_check->submitted_items) {
    //         return false;
    //     }
    //     $blending_items_status_check = DB::table('blending_items')
    //                                     ->leftJoin('blending_measures', function ($join) use ($daily_measures_id) {
    //                                         $join->on('blending_measures.blending_item_id', '=', 'blending_items.id')
    //                                             ->where('blending_measures.daily_measure_id', '=', $daily_measures_id);
    //                                     })
    //                                     ->where('blending_items.status', 'Active')
    //                                     ->selectRaw('
    //                                         COUNT(blending_items.id) as total_items,
    //                                         SUM(CASE WHEN blending_measures.status IN ("Verified") THEN 1 ELSE 0 END) as submitted_items')
    //                                     ->first();
    //                                     // dd($blending_items_status_check);
    //     if ($blending_items_status_check->total_items != $blending_items_status_check->submitted_items) {
    //         return false;
    //     }

    //     $mix_items_status_check = DB::table('mixing_items')
    //                                 ->leftJoin('mixing_measures', function ($join) use ($daily_measures_id) {
    //                                     $join->on('mixing_measures.mixing_item_id', '=', 'mixing_items.id')
    //                                         ->where('mixing_measures.daily_measure_id', '=', $daily_measures_id);
    //                                 })
    //                                 ->where('mixing_items.status', 'Active')
    //                                 ->selectRaw('
    //                                     COUNT(mixing_items.id) as total_items,
    //                                     SUM(CASE WHEN mixing_measures.status IN ("Verified") THEN 1 ELSE 0 END) as submitted_items')
    //                                 ->first();
    //     if ($mix_items_status_check->total_items != $mix_items_status_check->submitted_items) {
    //         return false;
    //         // return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
    //     }
    //     $md_items_status_check = DB::table('metal_detector_items')
    //                                 ->leftJoin('metal_detector_measures', function ($join) use ($daily_measures_id) {
    //                                     $join->on('metal_detector_measures.metal_detector_item_id', '=', 'metal_detector_items.id')
    //                                         ->where('metal_detector_measures.daily_measure_id', '=', $daily_measures_id);
    //                                 })
    //                                 ->where('metal_detector_items.status', 'Active')
    //                                 ->selectRaw('
    //                                     COUNT(metal_detector_items.id) as total_items,
    //                                     SUM(CASE WHEN metal_detector_measures.status IN ("Verified") THEN 1 ELSE 0 END) as submitted_items')
    //                                 ->first();
    //     if ($md_items_status_check->total_items != $md_items_status_check->submitted_items) {
    //         return false;
    //         // return redirect()->back()->with('error', 'Please check - all the measurement records not yet submitted.');
    //     }
    //     $update_daily_measure = DailyMeasure::where('id', $daily_measures_id)
    //                                             ->update(['status' => 'completed', 'is_lock' => '1','end_time' => date('H:i:s')]);
    // }

    public static function is_all_category_item_completed($daily_measures_id = NULL){
        $check_md_measure_item = MetalDetectorMeasure::where('daily_measure_id', $daily_measures_id)
                                                    ->where('status', '!=', 'Verified')
                                                    ->count();
        if($check_md_measure_item > 0){
            return false;
        }

        $check_mix_measure_item = MixingMeasure::where('daily_measure_id', $daily_measures_id)
                                                    ->where('status', '!=', 'Verified')
                                                    ->count();
        if($check_mix_measure_item > 0){
            return false;
        }

        $check_blending_measure_item = BlendingMeasure::where('daily_measure_id', $daily_measures_id)
                                                    ->where('status', '!=', 'Verified')
                                                    ->count();
        if($check_blending_measure_item > 0){
            return false;
        }

        $check_guacamole_measure_item = BlendingMeasure::where('daily_measure_id', $daily_measures_id)
                                                    ->where('status', '!=', 'Verified')
                                                    ->count();
        if($check_guacamole_measure_item > 0){
            return false;
        }

        $update_daily_measure = DailyMeasure::where('id', $daily_measures_id)
                                                ->update(['status' => 'completed', 'is_lock' => '1','end_time' => date('H:i:s')]);
    }

    public static function unverified_daily_measure_row($daily_measures_id = NULL){
        DailyMeasure::where('id', $daily_measures_id)
                        ->update(['status' => 'pending', 'is_lock' => '0','end_time' => "00:00:00"]);
    }

    // public static function read_lot_no_from_image($imagePath = NULL){

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer '.env('LLAMACLOUD_API_KEY'),
    //     ])->attach(
    //         'file', file_get_contents($imagePath), basename($imagePath)
    //     )->post('https://api.cloud.llamaindex.ai/api/v1/parsing/upload');

    //     $documentId = $response->json()['id'];
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://api.cloud.llamaindex.ai/api/parsing/job/'.$documentId.'/result/markdown',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    //     CURLOPT_HTTPHEADER => array(
    //         'Authorization: Bearer '.env('LLAMACLOUD_API_KEY')
    //     ),
    //     ));
    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     if (!empty(json_decode($response))) {
    //         $return_text = json_decode($response)->markdown;
    //         if (preg_match('/Lot:?\s*(\S+)/i', $return_text, $matches)) {
    //             $lot_number = $matches[1];
    //         }else{
    //             $lot_number = '';
    //         }
    //     }else {
    //         $lot_number = 'Please Scan again.';
    //     }
    //     return $lot_number ?: "Please Re-Scan.";
    // }


    public static function check_parsing_check_by_id($documentId = NULL){
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            sleep(2);

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cloud.llamaindex.ai/api/parsing/job/'.$documentId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('LLAMACLOUD_API_KEY')
            ),
            ));
            $response = curl_exec($curl);

            if (isset($resultData['status']) && $resultData['status'] === 'COMPLETED') {
                return true;
            }
            $attempt++;
        }
        return "Processing timeout. Please try again later.";
    }

    public static function read_lot_no_from_image($imagePath = NULL){

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('LLAMACLOUD_API_KEY'),
        ])->attach(
            'file', file_get_contents($imagePath), basename($imagePath)
        )->post('https://api.cloud.llamaindex.ai/api/v1/parsing/upload');

        $documentId = $response->json()['id'];
        $check_parsing_status = self::check_parsing_check_by_id($documentId);

        if ($check_parsing_status) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.cloud.llamaindex.ai/api/parsing/job/'.$documentId.'/result/markdown',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.env('LLAMACLOUD_API_KEY')
                ),
            ));

            $response_for_lot_number = curl_exec($curl);
            curl_close($curl);
            $decoded_response = json_decode($response_for_lot_number, true);
            // return $decoded_response;
            // dd($decoded_response);
            if (!empty($decoded_response) && isset($decoded_response['markdown'])) {
                $return_text = $decoded_response['markdown'];
                $lot_position = self::detect_x_code_for_lot_number($return_text);
                if ($lot_position) {
                    $lot_number = $lot_position;
                }else {
                    $lot_number = "Please scan again.";
                }
                // $lot_position = strpos($return_text, 'Lot:');
                // if ($lot_position !== false) {
                //     $lot_substring = substr($return_text, $lot_position + 4);
                //     $lot_parts = preg_split('/\s+/', trim($lot_substring));
                //     $lot_number = $lot_parts[0] ?? "Please scan again";
                // } else {
                //     $lot_number = "Please scan again.";
                // }

            } else {
                $lot_number = "Please scan again.";
            }
            return $lot_number ?: "Please scan again.";
        }
    }

    public static function detect_x_code_for_lot_number($markdown_text){
        preg_match('/\bX\d{7}\b/', $markdown_text, $matches);
        return $matches[0] ?? null;
    }

    public static function get_inspection_details_status($daily_measures_id = null, $item_id = null){
        $html = '';
        $status = Inspectiondetails::where('daily_measure_id', $daily_measures_id)
            // ->where('measure_categories_id', $measure_category_id)
            ->where('item_id', $item_id)
            ->value('status');

        if ($status === 'P') {
            return '
                <span class="text-center new_date_status badge_primary">
                    Verified
                </span>';
        }elseif ($status === 'S') {
            return '
                <span class="text-center new_date_status badge_submitted">
                    Submitted
                </span>';
        }elseif ($status === 'V') {
             return '
                <span class="text-center new_date_status badge_completed">
                    Approved
                </span>';
        }

        return '
            <span class="text-center new_date_status badge_pending">
                Pending
            </span>';
    }

    public static function check_user_role_map($user_id = null){
        $all_menu_access_rules = UserRoleMaping::where('user_id', $user_id)->get();
        $access_menu = [];
        if ($all_menu_access_rules->isNotEmpty()) {
            foreach($all_menu_access_rules as $all_menu_access_rules_data){
                switch ($all_menu_access_rules_data->role_id) {
                    case '1':
                        $access_menu['sample_collection_'] = true;
                        break;
                    case '2':
                        $access_menu['safety_manager'] = true;
                        break;
                    case '3':
                        $access_menu['operator'] = true;
                        break;

                    default:
                        return abort(503, 'User Role is not set.');
                        break;
                }
            }
        }else{
            return abort(503, 'User Role is not set.');
        }
        return $access_menu;
    }

    public static function print_html($get_all_category_items = null, $id_decode = null, $category_id = null){
        $html = '';
        if ($get_all_category_items->isNotEmpty()) {
            foreach ($get_all_category_items as $key => $get_all_category_items_data) {
                $html .= '<tr onclick="window.location=\'' . route('inspection_details_form', [
                            'measure_id'  => $id_decode,
                            // 'category_id' => $category_id,
                            'item_id'     => $get_all_category_items_data->item_id
                        ]) . '\'" style="cursor:pointer;">';
                // $html .='<a href="'.route('inspection_details_form', ['measure_id'=>$id_decode, 'category_id'=>$category_id, 'item_id' => $get_all_category_items_data->item_id]).'">';
                $html .='<td>';
                $item_unit = $get_all_category_items_data->item_unit ?? 'oz';
                $html .= $get_all_category_items_data->item_name." - ".($get_all_category_items_data->weight > 0 ? number_format($get_all_category_items_data->weight, 0) . " $item_unit" : "N/A");
                if(!empty($get_all_category_items_data->batch_no)){
                        $html .= '<span class="text-danger"> Batch no -' . $get_all_category_items_data->batch_no.' </span>';
                }
                $html .= '</td>';

                // $item_unit = $get_all_category_items_data->item_unit ?? 'oz';
                // $html .=  '<td>' . ($get_all_category_items_data->weight > 0 ? $get_all_category_items_data->weight . " $item_unit" : "N/A") . '</td>';
                $html .= '<td>'.self::get_inspection_status($get_all_category_items_data->inspectiondetails_id, "S").'</td>';
                $html .= '<td>'.self::get_inspection_status($get_all_category_items_data->inspectiondetails_id, "P").'</td>';
                $html .= '<td>'.self::get_inspection_status($get_all_category_items_data->inspectiondetails_id, "V").'</td>';
                $html .= '<td class="text-center">'.WebHookHelper::get_inspection_details_status($id_decode, $category_id, $get_all_category_items_data->item_id).'</td>';

                $html .= '<td>
                    <a href="'.route('inspection_details_form', ['measure_id'=>$id_decode, 'category_id'=>$category_id, 'item_id' => $get_all_category_items_data->item_id]).'">
                        <i class="bi bi-chevron-double-right text_blending"></i>
                    </a>
                </td>
            </tr>';
            // $html .='</a>';
            }
        } else {
            $html .= '<tr><td colspan="6">' .no_record_found_in_table().'</td><tr>';
        }

        return $html;
    }

    public static function get_inspection_status($inspection_id = null, $inspection_status = null){
        $data = InspectionStatus::join('users', 'users.id', '=', 'inspection_statuses.user_id')
            ->where('inspection_statuses.inspection_id', $inspection_id)
            ->where('inspection_statuses.inspec_status', $inspection_status)
            ->value('users.name');
        $short_name = trim( Str::before($data, ' ') . ' ' . Str::upper(Str::substr(Str::afterLast($data, ' '), 0, 1)) );
        // dd($short_name);
        return $short_name ?? '-';
    }

    public static function getMonthWeekFromYearWeek($year, $week)
    {
        $date = Carbon::now()->setISODate($year, $week);
        return [
            'month' => $date->format('F'),
            'week_of_month' => $date->weekOfMonth,
            'start_date' => $date->copy()->startOfWeek()->format('d-m-Y'),
            'end_date' => $date->copy()->endOfWeek()->format('d-m-Y'),
        ];
    }
}
?>
