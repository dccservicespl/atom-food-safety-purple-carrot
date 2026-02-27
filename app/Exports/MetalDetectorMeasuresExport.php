<?php

namespace App\Exports;

use App\Models\MetalDetectorMeasure;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MetalDetectorMeasuresExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $daily_measure_id;

    public function __construct($daily_measure_id)
    {
        $this->daily_measure_id = $daily_measure_id;
    }

    public function collection()
    {
        return MetalDetectorMeasure::join('metal_detector_items', 'metal_detector_items.id', '=', 'metal_detector_measures.metal_detector_item_id')
            ->join('users', 'users.id', '=', 'metal_detector_measures.created_by')
            ->where('metal_detector_measures.daily_measure_id', $this->daily_measure_id)
            ->select([
                'metal_detector_items.item_name',
                'metal_detector_items.weight',
                'metal_detector_measures.*',
            ])
            ->orderBy('metal_detector_measures.id', 'ASC')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['FOOD SAFETY'],
            ['Directions: Food Safety will monitor all metal detector functionality by placing approved standars on finished product container at the start of every production run prior to passing product through, and every hour during processing. Note any rejected product required.'],
            [],
            ['Corrective Actions: If a metal detector fails to detect and reject/stop, food safety must place metal detector and product  on hold back to the last good check for management disposition review.'],
            [],
            [
                "If any standard does not detect or reject, contact management for support. Verify the metal detector functionality with all three standards after the auto setup is complete. If all standards detect and reject/stop the product, proceed with passing the product. If not, repeat the auto setup.
    Hold the metal detector if it fails to detect and reject/stop all standards after three auto setup attempts. You may run the product through another functioning metal detector."
            ],
            [],
            ['Metal Detector Model', 'Kick out', 'Belt Stop'],
            [],
            ['Time', 'Product Description', '2.0mm FE Pass / Fail', '3.0mm Nfe Pass / Fail', '4.0mm SS Pass / Fail', 'Confirm Label', 'Comments', 'Initials'],
        ];
    }

    public function map($row): array
    {
        return [
            $row->updated_at->format('g:i A'),
            $row->item_name,
            $row->mm_2_fe == "N"?"N/A":$row->mm_2_fe,
            $row->mm_3_nfe == "N"?"N/A":$row->mm_3_nfe,
            $row->mm_4_ss == "N"?"N/A":$row->mm_4_ss,
            $row->confirm_label == "N"?"N/A":$row->confirm_label,
            $row->comments ?? 'N/A',
            $row->initial ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // $metal_detector_measure_fst = DB::table('metal_detector_measures')->join('users', 'users.id', 'metal_detector_measures.created_by')->latest('measure_date')->first();
        // $measure_date = date('m-d-Y', strtotime($metal_detector_measure_fst->measure_date)) ?? 'N/A';
        // $created_by = $metal_detector_measure_fst->name ?? 'N/A';
        // $metal_detector_measure = DB::table('metal_detector_measures')->join('users', 'users.id', 'metal_detector_measures.reviewed_by')->latest('reviewed_at')->first();
        // $reviewed_by = $metal_detector_measure->name ?? 'N/A';
        // $reviewed_date = date('m-d-Y', strtotime($metal_detector_measure->reviewed_at)) ?? 'N/A';

        $get_created_and_review_details = DB::table('metal_detector_measures')
            ->where('metal_detector_measures.daily_measure_id', $this->daily_measure_id)
            ->leftJoin('users as created_user', 'created_user.id', '=', 'metal_detector_measures.created_by')
            ->leftJoin('users as review_user', 'review_user.id', '=', 'metal_detector_measures.reviewed_by')
            ->select('created_user.name as created_by_name', 'review_user.name as review_by_name', 'metal_detector_measures.created_at', 'metal_detector_measures.reviewed_at')
            ->get();
        $created_by_names = $get_created_and_review_details->pluck('created_by_name')->unique()->implode(', ');
        $review_by_names = $get_created_and_review_details->pluck('review_by_name')->unique()->implode(', ');

        $get_created_and_review_data = [
            'created_by_name' => $created_by_names,
            'created_date' => $get_created_and_review_details->first()->created_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->created_at)) : '',
            'review_by_name' => $review_by_names,
            'reviewed_date' => $get_created_and_review_details->first()->reviewed_at ? date('m-d-Y', strtotime($get_created_and_review_details->first()->reviewed_at)) : '',
        ];

        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FF0000']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $directions_text = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $directions_part_1 = $directions_text->createTextRun("Directions: ");
        $directions_part_1->getFont()->setBold(true);
        $directions_part_2 = $directions_text->createText("Food Safety will monitor all metal detector functionality by placing approved standards on finished product container at the start of every production run prior to passing product through, and every hour during processing. Note any rejected product required.");

        $corrective_actions_text = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $corrective_actions_part_1 = $corrective_actions_text->createTextRun("Corrective Actions: ");
        $corrective_actions_part_1->getFont()->setBold(true);
        $corrective_actions_part_2 = $corrective_actions_text->createText("If a metal detector fails to detect and reject/stop, food safety must place metal detector and product on hold back to the last good check for management disposition review.");

        $sheet->setCellValue('A2', $directions_text);
        $sheet->setCellValue('A4', $corrective_actions_text);

        $sheet->mergeCells('A2:H3');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['wrapText' => true]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(-1);

        $sheet->mergeCells('A4:H5');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['wrapText' => true]
        ]);
        $sheet->getRowDimension(4)->setRowHeight(-1);

        $sheet->mergeCells('A6:H6');
        $sheet->getStyle('A6')->applyFromArray([
            'alignment' => ['wrapText' => true]
        ]);
        $sheet->getRowDimension(6)->setRowHeight(-1);

        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        $sheet->getStyle('B8:C8')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D0D0D0']
            ]
        ]);

        $sheet->getStyle('A10:H10')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '000000'],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $highest_row = $sheet->getHighestRow();
        $sheet->getStyle("A8:H$highest_row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ]
            ]
        ]);

        $new_row = $highest_row + 2;

        $sheet->setCellValue("A$new_row", "Notes / Comments");
        $sheet->mergeCells("A$new_row:H$new_row");
        $sheet->getStyle("A$new_row")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]
        ]);

        $new_row += 2;
        $sheet->setCellValue("A$new_row", "Food Safety Technician:");
        $sheet->setCellValue("B$new_row", $get_created_and_review_data['created_by_name']);
        $sheet->setCellValue("F$new_row", "Date:");
        $sheet->setCellValue("G$new_row", $get_created_and_review_data['created_date']);
        $sheet->mergeCells("B$new_row:E$new_row");
        $sheet->mergeCells("G$new_row:H$new_row");

        $sheet->getStyle("A$new_row:H$new_row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);

        $new_row++;
        $sheet->setCellValue("A$new_row", "Reviewed by:");
        $sheet->setCellValue("B$new_row", $get_created_and_review_data['review_by_name']);
        $sheet->setCellValue("F$new_row", "Date:");
        $sheet->setCellValue("G$new_row", $get_created_and_review_data['reviewed_date']);
        $sheet->mergeCells("B$new_row:E$new_row");
        $sheet->mergeCells("G$new_row:H$new_row");

        $sheet->getStyle("A$new_row:H$new_row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);

        $sheet->getStyle("B" . ($new_row - 1) . ":E$new_row")->applyFromArray([
            'borders' => ['bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        $sheet->getStyle("G" . ($new_row - 1) . ":H$new_row")->applyFromArray([
            'borders' => ['bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

        // Wrap text for all columns
        foreach (range('A', 'H') as $col) {
            $sheet->getStyle($col . '8')->getAlignment()->setWrapText(true);
            $sheet->getStyle($col . '9:' . $col . $highest_row)->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension($col)->setAutoSize(false);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(11);
        $sheet->getColumnDimension('D')->setWidth(11);
        $sheet->getColumnDimension('E')->setWidth(11);
        $sheet->getColumnDimension('F')->setWidth(9);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(13);
    }
}
