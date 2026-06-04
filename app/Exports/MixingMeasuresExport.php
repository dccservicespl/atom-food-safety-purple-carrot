<?php

namespace App\Exports;

use App\Models\MixingMeasure;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MixingMeasuresExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $daily_measure_id;

    public function __construct($daily_measure_id)
    {
        $this->daily_measure_id = $daily_measure_id;
    }

    public function collection()
    {
        $data = MixingMeasure::join('mixing_items', 'mixing_items.id', '=', 'mixing_measures.mixing_item_id')
            ->join('users', 'users.id', '=', 'mixing_measures.created_by')
            ->where('mixing_measures.daily_measure_id', $this->daily_measure_id)
            ->select([
                'mixing_items.item_name',
                'mixing_items.weight',
                'mixing_measures.*',
            ])
            ->orderBy('mixing_measures.id', 'ASC')
            ->get();

        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                $row->item_name,
                $row->weight,
                $row->odor,
                $row->appearance,
                '1 - ' . (floatval($row->weight_1) ?? 'N/A'),
                '3 - ' . (floatval($row->weight_3) ?? 'N/A'),
                '1 - ' . (floatval($row->temperature_1) ?? 'N/A'),
                $row->table_line,
                $row->scale
            ];

            $formattedData[] = [
                '',
                '',
                '',
                '',
                '2 - ' . (floatval($row->weight_2) ?? 'N/A'),
                '4 - ' . (floatval($row->weight_4) ?? 'N/A'),
                '2 - ' . (floatval($row->temperature_2) ?? 'N/A'),
                '',
                ''
            ];
        }

        return collect($formattedData);
    }

    public function headings(): array
    {
        return [
            'Item Description',
            'Net Weight',
            'Odor',
            'Appearance',
            'Weight Checks (Net weight)',
            'Temperature Check °F',
            'Table # / Line',
            'Scale #'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                // $mixing_measure_fst = DB::table('mixing_measures')->join('users', 'users.id', 'mixing_measures.created_by')->latest('measure_date')->first();
                // $measure_date = date('m-d-Y', strtotime($mixing_measure_fst->measure_date)) ?? 'N/A';
                // $created_by = $mixing_measure_fst->name ?? 'N/A';
                // $mixing_measure = DB::table('mixing_measures')->join('users', 'users.id', 'mixing_measures.reviewed_by')->latest('reviewed_at')->first();
                // $reviewed_by = $mixing_measure->name ?? 'N/A';
                // $reviewed_date = date('m-d-Y', strtotime($mixing_measure->reviewed_at)) ?? 'N/A';

                $get_created_and_review_details = DB::table('mixing_measures')
                    ->where('mixing_measures.daily_measure_id', $this->daily_measure_id)
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

                $sheet = $event->sheet->getDelegate();
                $row_count = $event->sheet->getHighestRow();

                $item_table_range = "A1:I{$row_count}";
                $sheet->getStyle($item_table_range)->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Define colors
                $colors = ['FFFFB9', 'FFDE75', 'FF8989', '93E3FF', 'FBE2D5', 'FFAFAF', 'C9E7A7', '94DCF8'];
                $color_index = 0;

                for ($row = 2; $row <= $row_count; $row++) {
                    $color = $colors[$color_index % count($colors)];
                    $sheet->getStyle("A{$row}")
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $color_index++;
                }

                $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('D0D0D0');
                $sheet->getStyle('A1:I1')->getFont()->setBold(true);
                $sheet->getStyle('A:I')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('E1:F1');
                $sheet->setCellValue('G1', 'Temperature Check (°F)');
                $sheet->setCellValue('H1', 'Table # / Line');
                $sheet->setCellValue('I1', 'Scale #');

                for ($row = 2; $row <= $row_count; $row += 2) {
                    $sheet->mergeCells("A{$row}:A" . ($row + 1));
                    $sheet->mergeCells("B{$row}:B" . ($row + 1));
                    $sheet->mergeCells("C{$row}:C" . ($row + 1));
                    $sheet->mergeCells("D{$row}:D" . ($row + 1));
                    $sheet->mergeCells("H{$row}:H" . ($row + 1));
                    $sheet->mergeCells("I{$row}:I" . ($row + 1));
                }

                $footer_start_row = $row_count + 3;

                $sheet->mergeCells("A{$footer_start_row}:I{$footer_start_row}");
                $sheet->setCellValue("A{$footer_start_row}", "Temperature Limits: The final packaged product must be maintained at or below 40°F during both storage and processing.");

                $notes_row = $footer_start_row + 2;
                $sheet->setCellValue("A{$notes_row}", "Notes:");
                $sheet->mergeCells("B{$notes_row}:I" . ($notes_row + 2));

                $review_row = $notes_row + 5;
                $sheet->mergeCells("A{$review_row}:I{$review_row}");
                $sheet->setCellValue("A{$review_row}", "COMPLETED & REVIEWED BY:");
                $sheet->getStyle("A{$review_row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$review_row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $tech_row = $review_row;

                $print_row_1 = $tech_row + 1;
                $sheet->setCellValue("A{$print_row_1}", "Food Safety Technician:");
                $sheet->mergeCells("B{$print_row_1}:E{$print_row_1}");
                $sheet->setCellValue("B{$print_row_1}", "{$get_created_and_review_data['created_by_name']}");

                $sheet->setCellValue("G{$print_row_1}", "Date:");
                $sheet->mergeCells("H{$print_row_1}:I{$print_row_1}");
                $sheet->setCellValue("H{$print_row_1}", "{$get_created_and_review_data['created_date']}");

                $reviewed_row = $print_row_1;

                $print_row_2 = $reviewed_row + 1;
                $sheet->setCellValue("A{$print_row_2}", "Reviewed by:");
                $sheet->mergeCells("B{$print_row_2}:E{$print_row_2}");
                $sheet->setCellValue("B{$print_row_2}", "{$get_created_and_review_data['review_by_name']}");

                $sheet->setCellValue("G{$print_row_2}", "Date:");
                $sheet->mergeCells("H{$print_row_2}:I{$print_row_2}");
                $sheet->setCellValue("H{$print_row_2}", "{$get_created_and_review_data['reviewed_date']}");

                $border_range = "A{$footer_start_row}:I{$print_row_2}";
                $sheet->getStyle($border_range)->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getStyle($border_range)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                foreach (range('A', 'I') as $col) {
                    $sheet->getStyle($col)->getAlignment()->setWrapText(true);
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                }

                $sheet->getColumnDimension('A')->setWidth(20.36);
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(7);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(13);
                $sheet->getColumnDimension('F')->setWidth(13);
                $sheet->getColumnDimension('G')->setWidth(12.50);
                $sheet->getColumnDimension('H')->setWidth(8.86);
                $sheet->getColumnDimension('I')->setWidth(10);
            },
        ];
    }
}
