<?php

namespace App\Exports;

use App\Models\BlendingItems;
use App\Models\BlendingMeasure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BlendingMeasuresExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $daily_measure_id;
    protected $merged_cells = [];
    protected $item_groups = [];

    public function __construct($daily_measure_id)
    {
        $this->daily_measure_id = $daily_measure_id;
    }
    public function collection()
    {
        return BlendingMeasure::join('blending_items', 'blending_items.id', '=', 'blending_measures.blending_item_id')
            ->join('users', 'users.id', '=', 'blending_measures.created_by')
            ->where('blending_measures.daily_measure_id', $this->daily_measure_id)
            ->select(
                'blending_measures.*',
                'blending_items.item_name',
                'blending_items.ph_min',
                'blending_items.ph_max',
                'users.name'
            )
            ->orderBy('blending_items.item_name', 'ASC')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['FOOD SAFETY'],
            [
                'Blending Description',
                'Batch #',
                'pH Result',
                'Temperature °F',
                'Appearance P(Pass)/F(Fail)',
                'Odor P(Pass)/F(Fail)',
                'Taste P(Pass)/F(Fail)',
                'Comments / Corrective Actions',
                'Initial',
            ]
        ];
    }

    public function map($measure): array
    {
        return [
            $measure->item_name,
            $measure->batch_no,
            $measure->ph_result,
            $measure->temperature,
            $measure->appearance,
            $measure->odor,
            $measure->taste,
            $measure->comments ?? 'N/A',
            $measure->initial ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Retrieve measure details
        // $blending_measure_fst = DB::table('blending_measures')
        //     ->join('users', 'users.id', 'blending_measures.created_by')
        //     ->latest('measure_date')->first();
        // $measure_date = date('m-d-Y', strtotime($blending_measure_fst->measure_date)) ?? 'N/A';
        // $created_by = $blending_measure_fst->name ?? 'N/A';

        // $blending_measure = DB::table('blending_measures')
        //     ->join('users', 'users.id', 'blending_measures.reviewed_by')
        //     ->latest('reviewed_at')->first();
        // $reviewed_by = $blending_measure->name;
        // $reviewed_date = date('m-d-Y', strtotime($blending_measure->reviewed_at)) ?? 'N/A';

        $get_created_and_review_details = DB::table('blending_measures')
            ->where('blending_measures.daily_measure_id', $this->daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'blending_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'blending_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'blending_measures.created_at', 'blending_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)):'',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)):'',
        ];

        // Merge the cells for the header
        $sheet->mergeCells('A1:I1');

        // Apply styling to "FOOD SAFETY" header
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FF0000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Apply text wrapping and background color to the header row
        $sheet->getStyle('A2:I2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D0D0D0']],
            'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Auto-size columns and apply text wrapping
        foreach (range('A', 'I') as $column_id) {
            $sheet->getStyle("{$column_id}")->getAlignment()->setWrapText(true);
        }

        // Apply border style to the header row
        $sheet->getStyle('A2:I2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D0D0D0']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Get the highest row number
        $highest_row = $sheet->getHighestRow();
        $data = $this->collection()->toArray();

        $start_row = 0;
        $current_item = null;
        $item_count = 0;

        for ($i = 0; $i < count($data); $i++) {
            $item_name = $data[$i]['item_name'];
            if ($current_item != $item_name) {
                if ($item_count > 1) {
                    $end_row = $i + 2;
                    $this->merged_cells[] = "A{$start_row}:A{$end_row}";
                    $this->item_groups[] = ['start_row' => $start_row, 'end_row' => $end_row];
                }
                $current_item = $item_name;
                $start_row = $i + 3;
                $item_count = 1;
            } else {
                $item_count++;
            }
        }
        //handle last group
        if ($item_count > 1) {
            $end_row = $highest_row;
            $this->merged_cells[] = "A{$start_row}:A{$end_row}";
             $this->item_groups[] = ['start_row' => $start_row, 'end_row' => $end_row];
        }

        // Apply borders and wrap text to rows
        for ($row = 3; $row <= $highest_row; $row++) {
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'alignment' => ['wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }

        // Page setup for PDF export: Fit the content to one page
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1);

        // Optional: Set orientation and margins for PDF export
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.5);

        // Adjust column widths manually if needed to fit better (A to I)
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(9.43);
        $sheet->getColumnDimension('D')->setWidth(12.29);
        $sheet->getColumnDimension('E')->setWidth(14.50);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(15);

        // Define alternating colors for rows
        $colors = ['FFFFB9', 'FFDE75', 'FF8989', '93E3FF', 'FBE2D5', 'FFAFAF', 'C9E7A7', '94DCF8'];

        for ($row = 3; $row <= $highest_row; $row++) {
            $cell_range = "A{$row}:A{$row}";
            $is_merged = false;
            foreach ($this->merged_cells as $merged_range) {
                if (strpos($merged_range, "A{$row}") !== false) {
                    $is_merged = true;
                    break;
                }
            }
            if (!$is_merged) {
                $colorIndex = ($row - 3) % count($colors);
                $color = $colors[$colorIndex];
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                ]);
            }
        }

        // Merge cells for item names
        foreach ($this->merged_cells as $merged_range) {
            $sheet->mergeCells($merged_range);
            $sheet->getStyle(explode(":", $merged_range)[0])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle(explode(":", $merged_range)[0])->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFB9']],
            ]);
        }

        // Setup pH table and instructions
        $ph_table_start_row = $highest_row + 3;
        $sheet->setCellValue("A{$ph_table_start_row}", "pH Critical Limits - Less than 4.5");
        $sheet->mergeCells("A{$ph_table_start_row}:E{$ph_table_start_row}");
        $sheet->getStyle("A{$ph_table_start_row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Set headers for pH table
        $ph_table_header_row = $ph_table_start_row + 1;
        $sheet->setCellValue("A{$ph_table_header_row}", "Product");
        $sheet->setCellValue("B{$ph_table_header_row}", "pH Target");
        $sheet->setCellValue("D{$ph_table_header_row}", "Product");
        $sheet->setCellValue("E{$ph_table_header_row}", "pH Target");

        $sheet->getStyle("A{$ph_table_header_row}:E{$ph_table_header_row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D0D0D0']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Set pH values
        $ph_values = [
            ['Pico de Gallo', '1.7 to 3.5', 'Mango Salsa', '4.5 or less'],
            ['Hatch Pepper Salsa', '1.7 to 3.6', 'Fiery Salsa', '4.5 or less'],
            ['Guacamole - Spread, Mild, Medium, Hot Spicy & Hatch Pepper', '3.0 to 4.5', 'Tomato Medley', '4.5 or less'],
            ['', '', 'Taqueria Topping', '4.5 or less']
        ];

        $row = $ph_table_header_row + 1;
        foreach ($ph_values as $data) {
            $sheet->setCellValue("A{$row}", $data[0]);
            $sheet->setCellValue("B{$row}", $data[1]);
            $sheet->setCellValue("D{$row}", $data[2]);
            $sheet->setCellValue("E{$row}", $data[3]);

            $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);

            $row++;
        }

        // Corrective action text
        $corrective_action_row = $row + 2;
        $sheet->setCellValue("A{$corrective_action_row}", "Corrective Action: If pH fails, place the blending on hold and add extra lime juice 0.1lb at a time until the test passes. Document all re-test in the correction column, including the Extra amount of lime juice added and the Final pH result.");
        $sheet->mergeCells("A{$corrective_action_row}:E" . ($corrective_action_row + 1));
        $sheet->getStyle("A{$corrective_action_row}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Notes section
        $notes_row = $corrective_action_row + 3;
        $sheet->setCellValue("A{$notes_row}", "Notes:");
        $sheet->mergeCells("A{$notes_row}:E" . ($notes_row + 3));
        $sheet->getStyle("A{$notes_row}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Completed & reviewed section
        $completed_row = $notes_row + 5;
        $sheet->setCellValue("A{$completed_row}", "COMPLETED & REVIEWED BY:");
        $sheet->getStyle("A{$completed_row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);

        // Food Safety Technician row
        $sheet->setCellValue("A" . ($completed_row + 2), "Food Safety Technician:");
        $sheet->setCellValue("B" . ($completed_row + 2), $get_created_and_review_data['created_by_name']);
        $sheet->setCellValue("D" . ($completed_row + 2), "Date:");
        $sheet->setCellValue("E" . ($completed_row + 2), $get_created_and_review_data['created_date']);

        // Reviewed by row
        $sheet->setCellValue("A" . ($completed_row + 4), "Reviewed by:");
        $sheet->setCellValue("B" . ($completed_row + 4), $get_created_and_review_data['review_by_name']);
        $sheet->setCellValue("D" . ($completed_row + 4), "Date:");
        $sheet->setCellValue("E" . ($completed_row + 4), $get_created_and_review_data['reviewed_date']);

        $sheet->getStyle("A" . ($completed_row + 2) . ":F" . ($completed_row + 5))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getColumnDimension('A')->setWidth(27.82);
    }
}