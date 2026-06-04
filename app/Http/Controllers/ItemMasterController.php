<?php

namespace App\Http\Controllers;

use App\Models\Measure_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ItemMasterController extends Controller
{
    public function item_list($category_id = NULL){
        $category_decode_id = Crypt::decrypt($category_id);
        switch ($category_decode_id) {
            case '5':
                $table_name = 'blending_items';
                $view_name = 'item-master.blending-items.item-list';
                break;
            case '6':
                $table_name = 'mixing_items';
                $view_name = 'item-master.mix-items.item-list';
                break;
            case '7':
                $table_name = 'metal_detector_items';
                $view_name = 'item-master.metal-detector-items.item-list';
                break;
            case '8':
                $table_name = 'guacamole_items';
                $view_name = 'item-master.guacamole-items.item-list';
                break;

            default:
                # code...
                break;
        }

        $get_all_item = DB::table($table_name)->get();
        $get_category_data = Measure_category::where('id', $category_decode_id)->first();
        $get_route = route('item_master');
        return view($view_name, compact('get_all_item', 'get_category_data', 'get_route'));

    }
}
