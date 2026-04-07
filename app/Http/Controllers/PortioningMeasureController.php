<?php

namespace App\Http\Controllers;

use App\Imports\PortioningMultiSheetImport;
use App\Models\PurpleCarrotItemMst;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        return view('portioning_measurement_form.portioning_measurement_form', compact('item_details', 'get_route'));
    }

    public function item_form()
    {
        $get_route = '';
        return view('portioning_measurement_form.item_form', compact('get_route'));
    }
    public function portioning_measure_head()
    {
        $get_route = route('work_type');
        return view('portioning_measurement_form.portioning_measure_head', compact('get_route'));
    }

    public function portioning_measure_data_upload()
    {
        $get_route = route('portioning_measure_dashboard');
        return view('portioning_measurement_form.portioning_measure_data_upload', compact('get_route'));
    }

    public function portioning_measure_dashboard()
    {
        $get_route = route('work_type');
        return view('portioning_measurement_form.portioning_measure_dashboard', compact('get_route'));
    }

    public function week_details()
    {
        $get_route = route('work_type');
        return view('pages.week_details', compact('get_route'));
    }

    // public function portioning_measure_data_upload_action(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,csv',
    //     ]);

    //     $target_base_names = [
    //         '1200 Allergen',
    //         'Powder',
    //         'Granular',
    //         'Piston1200',
    //         'Sleek',
    //         'Piston',
    //         'Hand Allergen',
    //     ];

    //     // Read all sheet names from the uploaded file
    //     $spreadsheet = IOFactory::load($request->file('file')->getPathname());
    //     $all_sheet_names = $spreadsheet->getSheetNames();

    //     // Strip version suffix and match to target base names
    //     $resolved_sheets = [];

    //     foreach ($all_sheet_names as $sheet_name) {
    //         $base_name = trim(preg_replace('/\s+[\d.]+$/', '', $sheet_name));

    //         if (in_array($base_name, $target_base_names)) {
    //             $resolved_sheets[$sheet_name] = $base_name;
    //         }
    //     }

    //     $import = new PortioningMultiSheetImport();
    //     $import->setResolvedSheets($resolved_sheets);

    //     Excel::import($import, $request->file('file'));

    //     $final_array = $import->getData();
    //     // dd($final_array);

    //     return redirect()->route('portioning_measure_dashboard')->with('success', 'Data uploaded successfully!');
    // }

    public function portioning_measure_data_upload_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first('file'),
            ], 422);
        }

        $target_base_names = [
            '1200 Allergen',
            'Powder',
            'Granular',
            'Piston1200',
            'Sleek',
            'Piston',
            'Hand Allergen',
        ];

        // Read all sheet names from the uploaded file
        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $all_sheet_names = $spreadsheet->getSheetNames();
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The uploaded file could not be read. Please upload a valid Excel file.',
            ], 422);
        }

        // Strip version suffix and match to target base names
        $resolved_sheets = [];
        $matched_base_names = [];

        foreach ($all_sheet_names as $sheet_name) {
            $base_name = trim(preg_replace('/\s+[\d.]+$/', '', $sheet_name));

            if (in_array($base_name, $target_base_names)) {
                $resolved_sheets[$sheet_name] = $base_name;
                $matched_base_names[] = $base_name;
            }
        }

        // Check if NO sheets matched at all
        if (empty($resolved_sheets)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The uploaded Excel file does not match the required format. Please upload the correct Portioning Measure file.',
            ], 422);
        }

        // Check which required sheets are missing
        $missing_sheets = array_diff($target_base_names, $matched_base_names);

        if (!empty($missing_sheets)) {
            $missing_list = implode(', ', $missing_sheets);
            return response()->json([
                'status'  => 'error',
                'message' => "The following sheet(s) were not found: {$missing_list}. Please check the sheet names in the Excel file.",
            ], 422);
        }

        // All sheets matched — proceed with import
        $import = new PortioningMultiSheetImport();
        $import->setResolvedSheets($resolved_sheets);

        Excel::import($import, $request->file('file'));
        // $final_array = $import->getData();
        // dd($final_array);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data uploaded successfully.',
        ], 200);
    }
}
