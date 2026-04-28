<?php

namespace App\Http\Controllers;

use App\Imports\PortioningMultiSheetImport;
use App\Models\PortioningMeasureHead;
use App\Models\PortioningMeasurement;
use App\Models\PortioningOrderHead;
use App\Models\PurpleCarrotItemMst;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
        $past_sheets = PortioningOrderHead::select('portioning_order_heads.*', 'users.name')->leftjoin('users', 'users.id', 'portioning_order_heads.updated_by')->orderBy('order_head_id', 'desc')->get();
        return view('portioning_measurement_form.portioning_measure_data_upload', compact('get_route', 'past_sheets'));
    }

    public function portioning_measure_dashboard()
    {
        $get_route = route('work_type');
        return view('portioning_measurement_form.portioning_measure_dashboard', compact('get_route'));
    }

    public function week_details($week_id = null, $order_head_id = null)
    {
        $get_route = route('portioning_measure_dashboard');
        return view('pages.week_details', compact('get_route', 'week_id', 'order_head_id'));
    }

    public function portioning_measure_data_upload_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
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
        $resolved_sheets    = [];
        $matched_base_names = [];

        foreach ($all_sheet_names as $sheet_name) {
            $base_name = trim(preg_replace('/\s+[\d.]+$/', '', $sheet_name));

            // Only take the FIRST occurrence of each base name — skip duplicates
            if (in_array($base_name, $target_base_names) && !in_array($base_name, $matched_base_names)) {
                $resolved_sheets[$sheet_name] = $base_name;
                $matched_base_names[]         = $base_name;
            }
        }

        // Check if No sheets matched at all
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

        $final_array = $import->getData();

        $firstSheetRows = reset($final_array);
        $firstRow       = $firstSheetRows[0] ?? [];

        $fromDate = $firstRow['from_date'] ?? now()->toDateString();
        $toDate   = $firstRow['to_date']   ?? now()->addDays(6)->toDateString();
        $week     = $firstRow['week'] ?? Carbon::parse($firstRow['from_date'])->format('n.j');

        $check_head_exists = PortioningOrderHead::where('week', $week)
            ->where('from_date', $fromDate)
            ->where('to_date', $toDate)
            ->exists();

        if ($check_head_exists) {
            return response()->json([
                'status'  => 'error',
                'message' => "The data for Week {$week} ({$fromDate} to {$toDate}) has already been uploaded. Please upload a different week.",
            ], 422);
        }

        // Store the file
        $original_name = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension     = $request->file('file')->getClientOriginalExtension();
        $created_at    = now()->format('m_d_Y');
        $file_name     = $original_name . '_' . $created_at . '.' . $extension;
        $upload_path   = 'assets/portioning_excel';
        $file_path     = 'assets/portioning_excel/' . $file_name;

        // Create folder if it doesn't exist
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Actually move the file to the folder
        $request->file('file')->move($upload_path, $file_name);

        DB::beginTransaction();

        try {
            // Insert into order head table
            $order_head             = new PortioningOrderHead();
            $order_head->week       = $week;
            $order_head->from_date  = $fromDate;
            $order_head->to_date    = $toDate;
            $order_head->file_name   = $file_name;
            $order_head->file_path   = $file_path;
            $order_head->week_number = Carbon::parse($fromDate)->weekOfYear;
            $order_head->status = 'Not Started';
            $order_head->updated_by = auth()->id();
            $order_head->save();

            $order_head_id = $order_head->order_head_id;

            // Map sheet names to category_id
            $categoryMap = [];

            foreach (array_keys($final_array) as $sheetName) {
                $existing = DB::table('portioning_categories')
                    ->where('category_name', $sheetName)
                    ->first();

                if ($existing) {
                    $categoryMap[$sheetName] = $existing->category_id;
                } else {
                    $categoryId = DB::table('portioning_categories')->insertGetId([
                        'category_name' => $sheetName,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    $categoryMap[$sheetName] = $categoryId;
                }
            }

            // Collect all order details rows
            $batch = [];

            foreach ($final_array as $sheetName => $rows) {
                $categoryId = $categoryMap[$sheetName];

                foreach ($rows as $mappedRow) {
                    $batch[] = [
                        'order_head_id'          => $order_head_id,
                        'portioning_category_id' => $categoryId,
                        'scheduled_day'          => $mappedRow['scheduled_day'] ?? null,
                        'letter'                 => $mappedRow['letter'] ?? null,
                        'component_details'      => $mappedRow['component_details'] ?? null,
                        'label'                  => $mappedRow['label'] ?? null,
                        'weight'                 => $mappedRow['weight'] ?? null,
                        'quantity'               => $mappedRow['quantity'] ?? null,
                        'film_size'              => $mappedRow['film_size'] ?? null,
                        'allergen'               => $mappedRow['allergen'] ?? null,
                        'packaging'              => $mappedRow['packaging'] ?? null,
                        '95_percent'             => $mappedRow['95_percent'] ?? null,
                        'status' => 'Not Started',
                    ];
                }
            }

            // Insert in order details
            if (!empty($batch)) {
                DB::table('portioning_order_details')->insert($batch);
            }

            $total_qty = DB::table('portioning_order_details')
                ->where('order_head_id', $order_head_id)
                ->sum('quantity');

            // Update the total_qty in the order head table
            PortioningOrderHead::where('order_head_id', $order_head_id)
                ->update(['total_qty' => $total_qty]);

            DB::commit();

            return response()->json([
                'status'        => 'success',
                'message'       => 'Data uploaded successfully.',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            // Delete stored file if DB insert fails
            $full_path = $file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function portioning_measure_delete($id)
    {
        DB::beginTransaction();
        try {
            $decrypted_id = Crypt::decrypt($id);
            // Delete order head
            DB::table('portioning_order_heads')->where('order_head_id', $decrypted_id)->delete();

            $get_portioning_measure_head_ids = DB::table('portioning_measure_heads')->where('portioning_order_head_id', $decrypted_id)->pluck('id')->toArray();

            // Delete order details
            DB::table('portioning_order_details')->where('order_head_id', $decrypted_id)->delete();

            //  DB::table('portioning_measure_heads')->where('portioning_order_head_id', $decrypted_id)->delete();

            // DB::table('portioning_measurements')->where('measure_id', $get_portioning_measure_head_ids)->delete();

            // DB::table('portioning_measurement_samples')->where('measure_id', $get_portioning_measure_head_ids)->delete();

            DB::commit();

            return redirect()->route('portioning_measure_data_upload')->with('success', 'Data deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('portioning_measure_data_upload')->with('error', 'Error deleting sheet: ' . $e->getMessage());
        }
    }

    public function day_details()
    {
        $get_route = route('work_type');
        return view('pages.day_details', compact('get_route'));
    }

    public function download_portioning_excel(Request $request, $order_head_id)
    {
        $decrypted_id = Crypt::decrypt($order_head_id);
        $order_head = DB::table('portioning_order_heads')
            ->where('order_head_id', $decrypted_id)
            ->first();

        if (!$order_head) {
            abort(404, 'Record not found.');
        }

        $full_path = $order_head->file_path;

        if (!$order_head->file_path || !file_exists($full_path)) {
            abort(404, 'File not found.');
        }

        return response()->download($full_path, $order_head->file_name);
    }

    public function order_measure_details($order_head_id, $portioning_category_id)
    {
        $get_route = route('work_type');
        return view('pages.day_details', compact('get_route', 'order_head_id', 'portioning_category_id'));
    }

    public function portioning_measurement_form_new(){
        return view('pages.portioning-measurement-form');
    }

    public function portioning_report($order_head_id, $portioning_category_id)
    {
        $portioningHeads = PortioningMeasureHead::where('portioning_order_head_id', $order_head_id)
                ->where('portioning_category_id', $portioning_category_id)
                ->with('measure_by')
                ->orderBy('created_at', 'asc')
                ->first();

        // dd($portioningHeads,"Report for Order Head ID: {$order_head_id}, Portioning Category ID: {$portioning_category_id}");

        // if ($portioningHeads->isEmpty()) {
        //     return redirect()->back()->with('error', 'No data found for this date.');
        // }

        $dataset = $this->prepareDataset($portioningHeads);
        // dd($dataset, $portioningHeads);
        // dd($portioningHeads, $dataset);
        $date = $portioningHeads->scheduled_day;
        $pdf = Pdf::loadView('pdfs.portioning-measurement-report', [
            'dataset' => $dataset,
            'reportDate' => Carbon::parse($date)->format('m/d/Y'),
            'generatedDate' => Carbon::now()->format('m/d/Y')
        ]);

        $pdf->setPaper('a4', 'landscape');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Ingredient_Portioning_Form_' . $date . '.pdf');

    }

    private function prepareDataset($portioningHeads)
    {
        $reportLineItems = [];
        $get_portioning_measurement_data = PortioningMeasurement::where('measure_id', $portioningHeads->id)
            ->with(['samples', 'item_details'])
            ->orderBy('created_at', 'asc')
            ->get();
        // dd($portioningHeads, $get_portioning_measurement_data);
        foreach ($get_portioning_measurement_data as $measurement) {
            $samples = $measurement->samples;
            $item = $measurement->item_details;

            $reportLineItems[] = [
                'time' => Carbon::parse($measurement->created_at)->format('g:i A'),
                'product_description' => $item->component_details ?? 'N/A',
                'lot_number' => $measurement->lot_number ?? 'N/A',
                'temp' => $measurement->temperature ?? '',
                'allergen' => $measurement->allergen ?? 'WHEAT',
                'allergen_test_result' => $measurement->allergen_test_result ?? 'N/A',
                'pack_size' => $measurement->pack_size ?? '2ea',
                'sample_1' => $samples[0]->sample_value ?? 'N/A',
                'sample_2' => $samples[1]->sample_value ?? 'N/A',
                'sample_3' => $samples[2]->sample_value ?? 'N/A',
                'kit_letter' => $measurement->kit_letter ?? 'DM',
                'qty_produced' => $measurement->qty_produced ?? 'N/A',
                'fs_initial' => $measurement->fs_initial ?? 'LA',
            ];
        }

        $dataset[] = [
            'report_head' => [
                'start_time' => Carbon::parse($portioningHeads->start_time)->format('g:i A'),
                'end_time' => Carbon::parse($portioningHeads->end_time)->format('g:i A'),
                'measure_by' => $portioningHeads->measure_by->name ?? 'N/A',
                'equipment' => $portioningHeads->equipment,
                'table_name' => $portioningHeads->table_name,
                'people_qty' => $portioningHeads->people_qty,
                'scale' => $portioningHeads->scale,
                'pre_op_complete' => ($portioningHeads->pre_op_complete==1?"Yes":"No"),
            ],
            'report_line_items' => $reportLineItems
        ];
        return $dataset;
    }

// private function prepareDataset2($portioningHeads){
//         $dataset = [];

//         foreach ($portioningHeads as $head) {
//             $reportLineItems = [];

//             foreach ($head->portioningMeasurements as $measurement) {
//                 $samples = $measurement->samples;
//                 $item = $measurement->item;

//                 $reportLineItems[] = [
//                     'time' => Carbon::parse($measurement->created_at)->format('g:i A'),
//                     'product_description' => $item->component_details ?? 'N/A',
//                     'lot_number' => $measurement->lot_number ?? 'N/A',
//                     'temp' => $measurement->temperature ?? '',
//                     'allergen' => $measurement->allergen ?? 'WHEAT',
//                     'allergen_test_result' => $measurement->allergen_test_result ?? 'N/A',
//                     'pack_size' => $measurement->pack_size ?? '2ea',
//                     'sample_1' => $samples[0]->sample_value ?? 'N/A',
//                     'sample_2' => $samples[1]->sample_value ?? 'N/A',
//                     'sample_3' => $samples[2]->sample_value ?? 'N/A',
//                     'kit_letter' => $measurement->kit_letter ?? 'DM',
//                     'qty_produced' => $measurement->qty_produced ?? 'N/A',
//                     'fs_initial' => $measurement->fs_initial ?? 'LA',
//                 ];
//             }

//             $dataset[] = [
//                 'report_head' => [
//                     'start_time' => Carbon::parse($head->start_time)->format('g:i A'),
//                     'end_time' => Carbon::parse($head->end_time)->format('g:i A'),
//                     'measure_by' => User::where('id', $head->measure_by)->value('name'),
//                     'equipment' => $head->equipment,
//                     'table_name' => $head->table_name,
//                     'people_qty' => $head->people_qty,
//                     'scale' => $head->scale,
//                     'pre_op_complete' => ($head->pre_op_complete==1?"Yes":"No"),
//                 ],
//                 'report_line_items' => $reportLineItems
//             ];
//         }

//         return $dataset;
//     }





}
