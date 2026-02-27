<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Models\BlendingItems;
use App\Models\DailyMeasure;
use App\Models\GuacamoleItems;
use App\Models\Inspectiondetails;
use App\Models\InspectionHead;
use App\Models\InspectionStatus;
use App\Models\LabelInspectionItemMst;
use App\Models\Measure_category;
use App\Models\MetalDetectorItem;
use App\Models\MixingItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

class LabelInspectionController extends Controller
{

    public function label_inspection_list($id = null){
        $id_decode = Crypt::decrypt($id);
        $get_route = route('dashboard');
        $get_the_measure_date = DailyMeasure::find($id_decode);

        if (Auth::user()->role_id == 1) {
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('label_inspection_item_msts', 'label_inspection_item_msts.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('label_inspection_item_msts.status', 'Active')
                ->whereIn('inspectiondetails.status', ['S', 'P', 'F', 'V'])
                ->orderBy('.item_name')
                ->select(
                    'inspectiondetails.id as inspectiondetails_id',
                    'label_inspection_item_msts.item_name',
                    'label_inspection_item_msts.weight',
                    'label_inspection_item_msts.item_unit',
                    'label_inspection_item_msts.id as item_id',
                    'inspectiondetails.status'
                )
                ->get();
        }elseif(Auth::user()->role_id == 2){
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('label_inspection_item_msts', 'label_inspection_item_msts.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('label_inspection_item_msts.status', 'Active')
                ->whereIn('inspectiondetails.status', ['P', 'F', 'V'])
                ->orderBy('label_inspection_item_msts.item_name')
                ->select(
                    'inspectiondetails.id as inspectiondetails_id',
                    'label_inspection_item_msts.item_name',
                    'label_inspection_item_msts.weight',
                    'label_inspection_item_msts.item_unit',
                    'label_inspection_item_msts.id as item_id',
                    'inspectiondetails.status'
                )
                ->get();
        }else {
            $get_all_category_items = DB::table('label_inspection_item_msts')
            ->where('label_inspection_item_msts.status', 'Active')
            ->leftJoin('inspectiondetails', function($join) use ($id_decode) {
                $join->on('label_inspection_item_msts.id', '=', 'inspectiondetails.item_id')
                    ->where('inspectiondetails.daily_measure_id', $id_decode);
            })
            ->select(
                'label_inspection_item_msts.id as item_id',
                'label_inspection_item_msts.item_name',
                'label_inspection_item_msts.weight',
                'label_inspection_item_msts.item_unit',
                'inspectiondetails.id as inspectiondetails_id',
                'inspectiondetails.status'
            )
            ->get();
        }
        // dd($get_all_category_items);

        $background_color = '#A982DD';
        $category_route = route('blending_label_inspection_listing', $id);
        return view('label-inspection.index', compact('background_color', 'category_route', 'get_all_category_items', 'get_the_measure_date', 'get_route', 'id_decode'));

    }

    public function label_inspection_list_filter(Request $request, $id){
        $id_decode = $id;

        $filter_item_name = $request->filter_item_name;
        $filter_status    = $request->filter_status;

        $query = DB::table('label_inspection_item_msts')
            ->where('label_inspection_item_msts.status', 'Active')
            ->leftJoin('inspectiondetails', function ($join) use ($id_decode) {
                $join->on('label_inspection_item_msts.id', '=', 'inspectiondetails.item_id')
                    ->where('inspectiondetails.daily_measure_id', $id_decode);
            });

        if (!empty($filter_status)) {
            $query->where('inspectiondetails.status', $filter_status);
        }

        if (!empty($filter_item_name)) {
            $query->where('label_inspection_item_msts.item_name', 'LIKE', '%' . $filter_item_name . '%');
        }

        $items = $query->select(
            'label_inspection_item_msts.id as item_id',
            'label_inspection_item_msts.item_name',
            'label_inspection_item_msts.weight',
            'label_inspection_item_msts.item_unit',
            'inspectiondetails.id as inspectiondetails_id',
            'inspectiondetails.status'
        )->get();

        $html = '';

        if ($items->count() > 0) {
            foreach ($items as $item) {

                // weight formatting logic (same as Blade)
                $weight = fmod($item->weight, 1) == 0
                    ? number_format($item->weight, 0)
                    : number_format($item->weight, 2);

                $routeUrl = route('inspection_details_form', [
                    'measure_id' => $id_decode,
                    'item_id'    => $item->item_id,
                ]);

                $html .= '<tr onclick="window.location=\'' . $routeUrl . '\'" style="cursor:pointer;">';

                // Item Name column
                $html .= '<td>';
                $html .= e($item->item_name) . ' - ' . $weight . ' ' . e($item->item_unit);
                $html .= '</td>';

                // Status columns (same helper calls)
                $html .= '<td>' . WebHookHelper::get_inspection_status($item->inspectiondetails_id, 'S') . '</td>';
                $html .= '<td>' . WebHookHelper::get_inspection_status($item->inspectiondetails_id, 'P') . '</td>';
                $html .= '<td>' . WebHookHelper::get_inspection_status($item->inspectiondetails_id, 'V') . '</td>';

                // Final status column
                $html .= '<td class="text-center">';
                $html .= WebHookHelper::get_inspection_details_status($id_decode, $item->item_id);
                $html .= '</td>';

                // Action column
                $html .= '<td>';
                $html .= '<a href="' . $routeUrl . '">';
                $html .= '<i class="bi bi-chevron-double-right text_blending"></i>';
                $html .= '</a>';
                $html .= '</td>';

                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="6" class="text-center text-muted">'.no_record_found_in_table().'</td>';
            $html .= '</tr>';
        }


        return response()->json([
            'html' => $html
        ]);
    }


    public function blending_label_inspection_listing($id = null){
        $id_decode = Crypt::decrypt($id);
        $get_route = route('dashboard');
        $category_id = 5;
        $get_the_measure_date = DailyMeasure::find($id_decode);

        if (Auth::user()->role_id == 1) {
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('blending_items', 'blending_items.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('inspectiondetails.measure_categories_id', $category_id)
                ->whereIn('inspectiondetails.status', ['S', 'P', 'F', 'V'])
                ->select('inspectiondetails.id as inspectiondetails_id','blending_items.item_name', 'blending_items.weight', 'blending_items.id as item_id')
                ->get();
        }elseif(Auth::user()->role_id == 2){
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('blending_items', 'blending_items.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('inspectiondetails.measure_categories_id', $category_id)
                ->whereIn('inspectiondetails.status', ['P', 'F', 'V'])
                ->select('inspectiondetails.id as inspectiondetails_id','blending_items.item_name', 'blending_items.weight', 'blending_items.id as item_id')
                ->get();
        }else {
            $get_all_category_items = DB::table('blending_items')
            ->leftJoin('inspectiondetails', function($join) use ($id_decode, $category_id) {
                $join->on('blending_items.id', '=', 'inspectiondetails.item_id')
                    ->where('inspectiondetails.daily_measure_id', $id_decode)
                    ->where('inspectiondetails.measure_categories_id', $category_id);
            })
            ->select(
                'blending_items.id as item_id',
                'blending_items.item_name',
                'blending_items.weight',
                'inspectiondetails.id as inspectiondetails_id',
                'inspectiondetails.status'
            )
            ->get();
        }
        $html = WebHookHelper::print_html($get_all_category_items, $id_decode, $category_id);

        $background_color = '#A982DD';
        $category_route = route('blending_label_inspection_listing', $id);
        return view('label-inspection.index', compact('background_color', 'category_route', 'html', 'get_all_category_items', 'get_the_measure_date', 'get_route', 'id_decode'));
    }

    public function mix_label_inspection_listing($id = null){
        $id_decode = Crypt::decrypt($id);
        $get_route = route('dashboard');
        $category_id = 6;

        if (Auth::user()->role_id == 1) {
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('mixing_items', 'mixing_items.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('inspectiondetails.measure_categories_id', 6)
                ->whereIn('inspectiondetails.status', ['S', 'P', 'F', 'V'])
                ->select('inspectiondetails.id as inspectiondetails_id', 'mixing_items.item_name', 'mixing_items.weight', 'mixing_items.id as item_id')
                ->get();
        }elseif(Auth::user()->role_id == 2){
            $get_all_category_items = DB::table('inspectiondetails')
                ->join('mixing_items', 'mixing_items.id', '=', 'inspectiondetails.item_id')
                ->where('inspectiondetails.daily_measure_id', $id_decode)
                ->where('inspectiondetails.measure_categories_id', 6)
                ->whereIn('inspectiondetails.status', ['P', 'F', 'V'])
                ->select('inspectiondetails.id as inspectiondetails_id','mixing_items.item_name', 'mixing_items.weight', 'mixing_items.id as item_id')
                ->get();
        }else{
            $get_all_category_items = DB::table('mixing_items')
                ->leftJoin('inspectiondetails', function($join) use ($id_decode, $category_id) {
                    $join->on('mixing_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id);
                })
                ->select(
                    'mixing_items.id as item_id',
                    'mixing_items.item_name',
                    'mixing_items.weight',
                    'inspectiondetails.id as inspectiondetails_id',
                    'inspectiondetails.status'
                )
                ->get();
        }
        $background_color = '#49C6E6';

        $html = WebHookHelper::print_html($get_all_category_items, $id_decode, $category_id);
        $get_the_measure_date = DailyMeasure::find($id_decode);
        return view('label-inspection.index', compact('background_color', 'html', 'get_the_measure_date', 'get_route', 'id_decode'));
    }

    public function md_label_inspection_listing($id = null){
        $id_decode = Crypt::decrypt($id);
        $get_route = route('dashboard');
        $category_id = 7;

        if (Auth::user()->role_id == 1) {
            $get_all_category_items = DB::table('inspectiondetails')
                        ->join('metal_detector_items', 'metal_detector_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->whereIn('inspectiondetails.status', ['S', 'P', 'F', 'V'])
                        ->select('inspectiondetails.id as inspectiondetails_id', 'metal_detector_items.item_name', 'metal_detector_items.weight','metal_detector_items.item_unit', 'metal_detector_items.id as item_id')
                        ->get();
        }elseif(Auth::user()->role_id == 2){
            $get_all_category_items = DB::table('inspectiondetails')
                        ->join('metal_detector_items', 'metal_detector_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->whereIn('inspectiondetails.status', ['P', 'F', 'V'])
                        ->select('inspectiondetails.id as inspectiondetails_id', 'metal_detector_items.item_name', 'metal_detector_items.weight','metal_detector_items.item_unit', 'metal_detector_items.id as item_id')
                        ->get();
        }else{
            $get_all_category_items = DB::table('metal_detector_items')
                ->leftJoin('inspectiondetails', function($join) use ($id_decode, $category_id) {
                    $join->on('metal_detector_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id);
                })
                ->select(
                    'metal_detector_items.id as item_id',
                    'metal_detector_items.item_name',
                    'metal_detector_items.weight',
                    'metal_detector_items.item_unit',
                    'inspectiondetails.id as inspectiondetails_id',
                    'inspectiondetails.status'
                )
                ->get();
        }
        $background_color = '#FBB231';
        $html = WebHookHelper::print_html($get_all_category_items, $id_decode, $category_id);
        $get_the_measure_date = DailyMeasure::find($id_decode);
        return view('label-inspection.index', compact('background_color', 'html', 'get_the_measure_date', 'get_route', 'id_decode'));
    }

    public function gua_label_inspection_listing ($id = null){
        $id_decode = Crypt::decrypt($id);
        $get_route = route('dashboard');
        $category_id = 8;

        if (Auth::user()->role_id == 1) {
            $get_all_category_items = DB::table('inspectiondetails')
                        ->join('guacamole_items', 'guacamole_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->whereIn('inspectiondetails.status', ['S', 'P', 'F', 'V'])
                        ->select('inspectiondetails.id as inspectiondetails_id', 'guacamole_items.item_name', 'guacamole_items.weight', 'guacamole_items.id as item_id')
                        ->get();
        }elseif(Auth::user()->role_id == 2){
            $get_all_category_items = DB::table('inspectiondetails')
                        ->join('guacamole_items', 'guacamole_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->whereIn('inspectiondetails.status', ['P', 'F', 'V'])
                        ->select('inspectiondetails.id as inspectiondetails_id', 'guacamole_items.item_name', 'guacamole_items.weight', 'guacamole_items.id as item_id')
                        ->get();
        }else{
            $get_all_category_items = DB::table('guacamole_items')
                ->leftJoin('inspectiondetails', function($join) use ($id_decode, $category_id) {
                    $join->on('guacamole_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id);
                })
                ->select(
                    'guacamole_items.id as item_id',
                    'guacamole_items.item_name',
                    'guacamole_items.weight',
                    'inspectiondetails.id as inspectiondetails_id',
                    'inspectiondetails.status'
                )
                ->get();
        }
        $background_color = '#EA724C';
        $html = WebHookHelper::print_html($get_all_category_items, $id_decode, $category_id);
        $get_the_measure_date = DailyMeasure::find($id_decode);
        return view('label-inspection.index', compact('background_color', 'html', 'get_the_measure_date', 'get_route', 'id_decode'));
    }

    public function get_category_item(Request $request){
        $id_decode = $request->id_decode;
        $category_id = $request->category_id;

        switch ($category_id) {
            case '5':
                if (Auth::user()->role_id == 2) {
                    $get_all_category_items = DB::table('inspectiondetails')
                        ->join('blending_items', 'blending_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->select('blending_items.item_name', 'blending_items.weight', 'blending_items.id as item_id')
                        ->get();
                }else {
                    $get_all_category_items = DB::table('blending_items')
                        ->select('blending_items.item_name', 'blending_items.weight', 'blending_items.id as item_id')
                        ->get();
                }

                $background_color = '#A982DD';
                break;

            case '6':
                if (Auth::user()->role_id == 2) {
                    $get_all_category_items = DB::table('inspectiondetails')
                        ->join('mixing_items', 'mixing_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->select('mixing_items.item_name', 'mixing_items.weight', 'mixing_items.id as item_id')
                        ->get();
                }else{
                    $get_all_category_items = DB::table('mixing_items')
                        ->select('mixing_items.item_name', 'mixing_items.weight', 'mixing_items.id as item_id')
                        ->get();
                }

                $background_color = '#49C6E6';
                break;

            case '7':
                if (Auth::user()->role_id == 2) {
                    $get_all_category_items = DB::table('inspectiondetails')
                        ->join('metal_detector_items', 'metal_detector_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->select('metal_detector_items.item_name', 'metal_detector_items.weight', 'metal_detector_items.id as item_id')
                        ->get();
                }else{
                    $get_all_category_items = DB::table('metal_detector_items')
                        ->select('metal_detector_items.item_name', 'metal_detector_items.weight', 'metal_detector_items.id as item_id')
                        ->get();
                }

                $background_color = '#FBB231';
                break;

            case '8':
                if (Auth::user()->role_id == 2) {
                    $get_all_category_items = DB::table('inspectiondetails')
                        ->join('guacamole_items', 'guacamole_items.id', '=', 'inspectiondetails.item_id')
                        ->where('inspectiondetails.daily_measure_id', $id_decode)
                        ->where('inspectiondetails.measure_categories_id', $category_id)
                        ->select('guacamole_items.item_name', 'guacamole_items.weight', 'guacamole_items.id as item_id')
                        ->get();
                }else{
                    $get_all_category_items = DB::table('guacamole_items')
                        ->select('guacamole_items.item_name', 'guacamole_items.weight', 'guacamole_items.id as item_id')
                        ->get();
                }


                $background_color = '#EA724C';
                break;

            default:
                # code...
                break;
        }

        $html = '';

        if ($get_all_category_items->isNotEmpty()) {
            foreach ($get_all_category_items as $key => $get_all_category_items) {
                $html .= '
                    <tr>
                        <td>';
                        $html .= $get_all_category_items->item_name;
                        if(!empty($get_all_category_items->batch_no)){
                                $html .= '<span class="text-danger"> Batch no -' . $get_all_category_items->batch_no.' </span>';
                        }
                $html .= '</td>
                        <td>' . ($get_all_category_items->weight > 0 ? $get_all_category_items->weight . " oz" : "N/A") . '</td>
                        <td class="text-center">'.WebHookHelper::get_inspection_details_status($id_decode, $category_id, $get_all_category_items->item_id).'</td>';

                $html .= '<td>
                    <a href="'.route('inspection_details_form', ['measure_id'=>$id_decode, 'category_id'=>$category_id, 'item_id' => $get_all_category_items->item_id]).'">
                        <i class="bi bi-chevron-double-right text_blending"></i>
                    </a>
                </td>
            </tr>';
            }
        } else {
            $html .= '<tr><td colspan="3">' .no_record_found_in_table().'</td><tr>';
        }

        return response()->json([
            'message' => 'Success',
            'data' => $html,
            'bg_color' => $background_color
        ]);

    }

    public function inspection_details_form(Request $request){

        $measure_id = $request->measure_id;
        // $category_id = $request->category_id;
        $item_id = $request->item_id;

        // switch ($category_id) {
        //     case '5':
        //         $get_the_item_details = BlendingItems::find($item_id);
        //         $route_name = 'blending_label_inspection_listing';
        //         break;
        //     case '6':
        //         $get_the_item_details = MixingItems::find($item_id);
        //         $route_name = 'mix_label_inspection_listing';
        //         break;
        //     case '7':
        //         $get_the_item_details = MetalDetectorItem::find($item_id);
        //         $route_name = 'md_label_inspection_listing';
        //         break;
        //     case '8':
        //         $get_the_item_details = GuacamoleItems::find($item_id);
        //         $route_name = 'gua_label_inspection_listing';
        //         break;

        //     default:
        //         abort(404, 'Category is Invalid.');
        //         break;
        // }
        $get_the_item_details = LabelInspectionItemMst::find($item_id);
        $route_name = 'label_inspection_list';

        $get_the_measure_date = DailyMeasure::find($measure_id);
         $existing_data = Inspectiondetails::where('daily_measure_id', $measure_id)
            // ->where('measure_categories_id', $category_id)
            ->where('item_id', $item_id)
            ->first();

        $request_data = array(
            'measure_id' => $measure_id,
            // 'category_id' => $category_id,
            'item_id' => $item_id,
        );

        $get_route = route($route_name, Crypt::encrypt($measure_id));

        return view('label-inspection.label_inspection_form', compact('route_name','existing_data', 'get_route', 'request_data', 'get_the_item_details', 'get_the_measure_date'));
    }

    public function inspection_details_form_action(Request $request){
        DB::beginTransaction();
        try {
             // Check if InspectionHead already exists
            $add_data_into_ins_head = InspectionHead::where('daily_measure_id', $request->measure_id)->first();

            // If not found, then insert
            if (!$add_data_into_ins_head) {
                $add_data_into_ins_head = new InspectionHead();
                $add_data_into_ins_head->daily_measure_id = $request->measure_id;
                $add_data_into_ins_head->save();
            }

            switch (Auth::user()->role_id) {
                case '1':
                        $check_all_check_pass = 'P';
                    break;
                    case '2':
                        $check_all_check_pass = 'V';
                        break;
                default:
                    $check_all_check_pass = 'S';
                    break;
            }


            $inspectionDetails = Inspectiondetails::where('daily_measure_id', $request->measure_id)
                // ->where('measure_categories_id', $request->category_id)
                ->where('item_id', $request->item_id)
                ->first();

            if (!$inspectionDetails) {
                $inspectionDetails = new Inspectiondetails();
                $inspectionDetails->daily_measure_id = $request->measure_id;
                $inspectionDetails->inspection_head_id = $add_data_into_ins_head->id;
                // $inspectionDetails->measure_categories_id = $request->category_id;
                $inspectionDetails->item_id = $request->item_id;
            }

            $inspectionDetails->coo_present = $request->coo_present;
            $inspectionDetails->best_by_accurate = $request->best_by_accurate;
            $inspectionDetails->nutritional_acts = $request->nutritional_acts;
            $inspectionDetails->allergen_statement = $request->allergen_statement;
            $inspectionDetails->ingredient_statement = $request->ingredient_statement;
            $inspectionDetails->barcode_clear = $request->barcode_clear;
            $inspectionDetails->verify_by = Auth::id();
            $inspectionDetails->verify_datetime = now();
            $inspectionDetails->note = $request->comments;
            $inspectionDetails->initials = $request->initials;
            $inspectionDetails->fs_initials = $request->fs_initials;
            $inspectionDetails->status = (Auth::user()->role_id == 2)?"V":$check_all_check_pass;

            if (Auth::user()->role_id == 1) {

                if ($request->hasFile('inspection_label_img')) {
                    $file = $request->file('inspection_label_img');
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = public_path('assets/img/label_inspection/' . $filename);

                    list($width, $height) = getimagesize($file->getRealPath());

                    $maxWidth = 800;
                    if ($width > $maxWidth) {
                        $newWidth = $maxWidth;
                        $newHeight = intval(($height / $width) * $newWidth);
                    } else {
                        $newWidth = $width;
                        $newHeight = $height;
                    }

                    $dst = imagecreatetruecolor($newWidth, $newHeight);
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (in_array($extension, ['jpg', 'jpeg'])) {
                        $src = imagecreatefromjpeg($file->getRealPath());
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagejpeg($dst, $path, 20);

                    } elseif ($extension === 'png') {
                        $src = imagecreatefrompng($file->getRealPath());
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagepng($dst, $path, 20);

                    } elseif ($extension === 'gif') {
                        $src = imagecreatefromgif($file->getRealPath());
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagegif($dst, $path);

                    } else {
                        $file->move(public_path('assets/img/label_inspection'), $filename);
                    }
                    $inspectionDetails->inspection_img = '/assets/img/label_inspection/' . $filename;
                }

            }

            $inspectionDetails->save();

            $add_record_in_inspection_status = new InspectionStatus();
            $add_record_in_inspection_status->inspection_id = $inspectionDetails->id;
            $add_record_in_inspection_status->user_id = Auth::user()->id;
            $add_record_in_inspection_status->inspec_status = $check_all_check_pass;
            $add_record_in_inspection_status->save();

            DB::commit();
            return redirect()->route($request->redirect_route, Crypt::encrypt($request->measure_id))->with('success', 'Inspection done successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(
                array(
                    'message' => $th->getMessage(),
                    'place' => 'inspection_details_form_action',
                    'line' => $th->getLine(),
                    'file' => $th->getFile()
                )
            );
            return redirect()->back()->with('error', 'Inspection Fail, Something went wrong.');
        }
    }

    public function download_inspection_report($daily_measure_id = null){
        $get_inspection_data = [];
        $get_daily_measure_date = DailyMeasure::where('id', $daily_measure_id)->value('measure_date');
        $get_inspection_data['get_daily_measure_date'] = $get_daily_measure_date;
        $get_inspection_data['blending_items'] = Inspectiondetails::join('label_inspection_item_msts', 'label_inspection_item_msts.id', '=', 'inspectiondetails.item_id')
            ->where('inspectiondetails.daily_measure_id', $daily_measure_id)
            ->where('inspectiondetails.status', 'V')
            ->select(
                'inspectiondetails.*',
                'label_inspection_item_msts.item_name',
                'label_inspection_item_msts.weight',
                'label_inspection_item_msts.item_unit'
                )
            ->get()
            ->toArray();

        // $get_inspection_data['blending_items'] = Inspectiondetails::join('blending_items', 'blending_items.id', '=', 'inspectiondetails.item_id')
        //     ->where('inspectiondetails.daily_measure_id', $daily_measure_id)
        //     ->where('inspectiondetails.measure_categories_id',5)
        //     ->where('inspectiondetails.status', 'V')
        //     ->select('inspectiondetails.*', 'blending_items.item_name', 'blending_items.weight')
        //     ->get()
        //     ->toArray();

        // $get_inspection_data['guacamole_items'] = Inspectiondetails::join('guacamole_items', 'guacamole_items.id', '=', 'inspectiondetails.item_id')
        //     ->where('inspectiondetails.daily_measure_id', $daily_measure_id)
        //     ->where('inspectiondetails.measure_categories_id',8)
        //     ->where('inspectiondetails.status', 'V')
        //     ->select('inspectiondetails.*', 'guacamole_items.item_name', 'guacamole_items.weight')
        //     ->get()
        //     ->toArray();

        // $get_inspection_data['mixing_items'] = Inspectiondetails::join('mixing_items', 'mixing_items.id', '=', 'inspectiondetails.item_id')
        //     ->where('inspectiondetails.daily_measure_id', $daily_measure_id)
        //     ->where('inspectiondetails.measure_categories_id',6)
        //     ->where('inspectiondetails.status', 'V')
        //     ->select('inspectiondetails.*', 'mixing_items.item_name', 'mixing_items.weight')
        //     ->get()
        //     ->toArray();

        // $get_inspection_data['metal_detector_items'] = Inspectiondetails::join('metal_detector_items', 'metal_detector_items.id', '=', 'inspectiondetails.item_id')
        //     ->where('inspectiondetails.daily_measure_id', $daily_measure_id)
        //     ->where('inspectiondetails.measure_categories_id',7)
        //     ->where('inspectiondetails.status', 'V')
        //     ->select('inspectiondetails.*', 'metal_detector_items.item_name', 'metal_detector_items.weight', 'metal_detector_items.item_unit')
        //     ->get()
        //     ->toArray();


        $all_users = Inspectiondetails::where('inspectiondetails.daily_measure_id', $daily_measure_id)
            ->join('inspection_statuses', 'inspection_statuses.inspection_id', '=', 'inspectiondetails.id')
            ->whereIn('inspection_statuses.inspec_status', ['S', 'P', 'V'])
            ->join('users', 'users.id', '=', 'inspection_statuses.user_id')
            ->select('users.name', 'inspection_statuses.inspec_status')
            ->get()
            ->groupBy('inspec_status');

        $get_operators_name = $all_users->get('S', collect())->pluck('name')->unique()->implode(', ');
        $get_inspector_name = $all_users->get('P', collect())->pluck('name')->unique()->implode(', ');
        $get_approver_name  = $all_users->get('V', collect())->pluck('name')->unique()->implode(', ');

        $get_inspection_data['get_operators_name'] = $get_operators_name;
        $get_inspection_data['get_inspector_name'] = $get_inspector_name;
        $get_inspection_data['get_approver_name'] = $get_approver_name;

        if (
            empty($get_inspection_data['blending_items'])
        ) {
            return redirect()->back()->with('error', 'No inspection data found.');
        }
        Log::info(
            [

            ]
        );
        $pdf = Pdf::loadView('label-inspection.download_inspection_report', $get_inspection_data)
                ->setPaper('a4', 'landscape');
        return $pdf->download('FRM QC-E-14.0 Label Verification Log_'.$daily_measure_id."_".date('mdy_his').'.pdf');
    }

    public function label_inspection_route($category_id = null){
        switch ($category_id) {
            case '5':
                $inspection_route = 'blending_label_inspection_listing';
                break;
            case '6':
                $inspection_route = 'mix_label_inspection_listing';
                break;
            case '7':
                $inspection_route = 'md_label_inspection_listing';
                break;
            case '8':
                $inspection_route = 'gua_label_inspection_listing';
                break;

            default:
                # code...
                break;
        }

        return $inspection_route;
    }

    public function remove_inspection_image(Request $request){
        $inspection_id = $request->id;
        $find_inspection_row = Inspectiondetails::find($inspection_id);

        $find_inspection_row->inspection_img = NULL;
        $find_inspection_row->update();
        return redirect()->back()->with('success', 'The Image has been removed successfully');
    }

    public function label_inspection_items(){
        $get_route = route('item_master');
        return view('label-inspection.items.index', compact('get_route'));
    }


}
