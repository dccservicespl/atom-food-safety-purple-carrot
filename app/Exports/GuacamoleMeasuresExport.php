<?php

namespace App\Exports;

use App\Models\DailyMeasure;
use App\Models\GuacamoleMeasure;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class GuacamoleMeasuresExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected $daily_measure_id;
    protected $groupedData;
    protected $sheet;
    protected $row_index = 1;

    public function __construct($daily_measure_id)
    {
        $this->daily_measure_id = $daily_measure_id;
    }

    public function collection()
    {
        $data = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', '=', 'guacamole_measures.guacamole_item_id')
            ->join('users', 'users.id', '=', 'guacamole_measures.created_by')
            ->where('guacamole_measures.daily_measure_id', $this->daily_measure_id)
            ->select([
                'guacamole_items.item_name',
                'guacamole_items.weight',
                'guacamole_measures.*',
            ])
            ->orderBy('guacamole_measures.id')
            ->orderBy('guacamole_measures.batch_no')
            ->orderBy('guacamole_items.weight')
            ->get();

        $grouped_measures = [];
        foreach ($data as $measure) {
            $group_key = $measure->item_name . ' (' . $measure->weight . ')';
            if (!isset($grouped_measures[$group_key])) {
                $grouped_measures[$group_key] = [];
            }
            $grouped_measures[$group_key][] = $measure;
        }

        $data_item = GuacamoleMeasure::join('guacamole_items', 'guacamole_items.id', 'guacamole_measures.guacamole_item_id')
            ->join('users', 'users.id', 'guacamole_measures.created_by')
            ->where('guacamole_measures.daily_measure_id', $this->daily_measure_id)
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

        $get_created_and_review_details = DB::table('guacamole_measures')
            ->where('guacamole_measures.daily_measure_id', $this->daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'guacamole_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'guacamole_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'guacamole_measures.created_at', 'guacamole_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)) : '',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)) : '',
        ];

        // Fetch Packaging Lot Numbers
        $packaging_lot_numbers = DB::table('guacamole_measures')
            ->join('guacamole_items', 'guacamole_items.id', '=', 'guacamole_measures.guacamole_item_id')
            ->where('guacamole_measures.daily_measure_id', $this->daily_measure_id)
            ->groupBy('guacamole_items.weight', 'guacamole_measures.cups', 'guacamole_measures.lids')
            ->selectRaw('
                guacamole_items.weight,
                guacamole_measures.cups,
                guacamole_measures.lids
            ')
            ->get();

        $packaging_lot_table = new Collection([
            ['Packaging Lot Numbers'],
            ['Size', 'CUPS', 'LIDS'],
        ]);

        foreach ($packaging_lot_numbers as $index => $lot) {
            $size = $lot->weight . ' oz' . str_repeat(' ', $index);
            $packaging_lot_table->push([
                $size,
                $lot->cups ?? 'N/A',
                $lot->lids ?? 'N/A',
            ]);
        }

        $product_summary_table = new Collection([
            ['Product', 'Total Containers Produced', 'Retains Collected', 'Best By Date', 'Initials', 'Batch Size (Total Weight)'],
        ]);

        foreach ($data_item as $index => $row) {
            $productName = $row->item_name . str_repeat("\u{200B}", $index);
            $product_summary_table->push([
                $productName ?? 'N/A',
                $row->total_containers ?? 'N/A',
                $row->retains_collected ?? 'N/A',
                Carbon::parse($row->best_by_date)->format('m-d-Y') ?? 'N/A',
                $row->initial ?? 'N/A',
                $row->weight . ' oz' ?? 'N/A',
            ]);
        }

        $last_entry = $data->last();
        $notes_table = new Collection([
            [''],
            ['Notes / Comments:'],
            [''],
            ['Food Safety Technician:', $get_created_and_review_data['created_by_name'], '', '', 'Date:', $get_created_and_review_data['created_date']],
            ['Reviewed by:', $get_created_and_review_data['review_by_name'], '', '', 'Date:', $get_created_and_review_data['reviewed_date']]
        ]);

        return $data->concat($packaging_lot_table)->concat($product_summary_table)->concat($notes_table);
    }

    public function map($row): array
    {
        if (isset($row['type']) && $row['type'] == 'header') {
            return [
                $row['item_name'],
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];
        }
        if (!isset($row->item_name)) {
            return (array) $row;
        }

        return [
            $row->item_name . ' ' . ($row->weight > 0 ? $row->weight . ' oz' : ''),
            $row->batch_no,
            floatval($row->temperature) . ' °F',
            $row->lot_number,
            $row->updated_at->format('g:i A'),

            "Fe- " . ($row->md_fe ?? 'N/A') .
                " | NFe- " . ($row->md_nfe ?? 'N/A') .
                " | St- " . ($row->md_st ?? 'N/A'),

            "1- " . ($row->sc_batch_1 ?? 'N/A') .
                " | 2- " . ($row->sc_batch_2 ?? 'N/A') .
                " | 3- " . ($row->sc_batch_3 ?? 'N/A') .
                " | 4- " . ($row->sc_batch_4 ?? 'N/A') .
                " | 5 - " . ($row->sc_batch_5 ?? 'N/A') .
                " | 6 - " . ($row->sc_batch_6 ?? 'N/A'),

            "1- " . (empty($row->oxygen_levels_1) ? 'N/A' : floatval($row->oxygen_levels_1)) .
                " | 2- " . (empty($row->oxygen_levels_2) ? 'N/A' : floatval($row->oxygen_levels_2)) .
                " | 3- " . (empty($row->oxygen_levels_3) ? 'N/A' : floatval($row->oxygen_levels_3)) .
                " | 4- " . (empty($row->oxygen_levels_4) ? 'N/A' : floatval($row->oxygen_levels_4)),

            "1- " . (empty($row->weight_checks_1) ? 'N/A' : floatval($row->weight_checks_1)) .
                " | 2- " . (empty($row->weight_checks_2) ? 'N/A' : floatval($row->weight_checks_2)) .
                " | 3- " . (empty($row->weight_checks_3) ? 'N/A' : floatval($row->weight_checks_3)) .
                " | 4- " . (empty($row->weight_checks_4) ? 'N/A' : floatval($row->weight_checks_4)),

            $row->initial ?? 'N/A',
            $row->md_model_result,
        ];
    }

    public function headings(): array
    {
        $guacamole_measure_fst = DB::table('guacamole_measures')
            ->join('users', 'users.id', '=', 'guacamole_measures.created_by')
            ->latest('measure_date')
            ->first();

        $production_date = date('m-d-Y', strtotime($guacamole_measure_fst->measure_date)) ?? 'N/A';
        $operator_name = $guacamole_measure_fst->name ?? 'N/A';

        $get_created_and_review_details = DB::table('guacamole_measures')
            ->where('guacamole_measures.daily_measure_id', $this->daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'guacamole_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'guacamole_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'guacamole_measures.created_at', 'guacamole_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)) : '',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)) : '',
        ];

        $get_operator_name = DailyMeasure::find($this->daily_measure_id);

        return [
            ["Production Date: $production_date"],
            ["Operator Name: " . $get_operator_name->guacamole_operator_name],
            [],
            [
                'Product',
                'Batch #',
                'Temp °F',
                'Mix Lot Number',
                'Time',
                'Metal Detector Check',
                'Seal Check',
                'Oxygen Levels (Less than 1.5%)',
                'Weight Verification',
                'Initials',
                'Metal Dectector Model'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $this->sheet = $sheet;
        $highestRow = $sheet->getHighestRow();

        // Apply styles to the entire sheet
        $sheet->getStyle("A1:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:K{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("E:J")->getAlignment()->setWrapText(true);

        // Set borders for the data section
        $sheet->getStyle("A4:K{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Style the headers
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A4:K4')->getFont()->setBold(true);

        // Background colors for headers
        $sheet->getStyle('A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('B4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('C4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('E4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('F4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('90EE90');
        $sheet->getStyle('G4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC0CB');
        $sheet->getStyle('H4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ADD8E6');
        $sheet->getStyle('I4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF99');
        $sheet->getStyle('J4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('K4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');

        // Style for Product Summary and Packaging Lot
        // $sheet->getStyle('A12:F12')->getFont()->setBold(true);
        // $sheet->getStyle('A12:F12')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
        $sheet->getStyle('A14:F14')->getFont()->setBold(true);
        // $sheet->getStyle('A15:F15')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);

        $this->mergeProductRows();
        return [];
    }

    private function mergeProductRows()
    {
        $start_row = 5;
        $highestRow = $this->sheet->getHighestRow();

        $current_value = null;
        $merge_start = null;

        for ($row = $start_row; $row <= $highestRow; $row++) {
            $value = $this->sheet->getCell("A{$row}")->getValue();

            if ($value !== $current_value) {
                if ($merge_start !== null && $row - 1 > $merge_start) {
                    // Merge previous group
                    $this->sheet->mergeCells("A{$merge_start}:A" . ($row - 1));
                    $this->sheet->getStyle("A{$merge_start}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }
                // Start new group
                $current_value = $value;
                $merge_start = $row;
            }
        }

        if ($merge_start !== null && $highestRow > $merge_start) {
            $this->sheet->mergeCells("A{$merge_start}:A{$highestRow}");
            $this->sheet->getStyle("A{$merge_start}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }
    }
}
