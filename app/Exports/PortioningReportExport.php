<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PortioningReportExport implements FromArray, WithEvents
{
    protected $dataset;
    protected $reportDate;

    public function __construct($dataset, $reportDate)
    {
        $this->dataset    = $dataset;
        $this->reportDate = $reportDate;
    }

    public function array(): array
    {
        $excelData = [];

        foreach ($this->dataset as $dataset_data) {
            $excelData[] = ['DATE: ' . $this->reportDate, '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['FOOD SAFETY', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['Ingredient Portioning Form', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = [
                'Procedure: Conduct an inspection of the product prior to start being portioned and every hour since then correct product, lot number, declare any allergen ingredients. Inspect 3 samples every hour, weight, seal, temperature, and Correct Allergen.',
                '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
            $excelData[] = [
                'IMPORTANT: Cleaning is required after running an allergen ingredient and an Allergen Swab test must be performed and must be record below indication P (Pass) or F (Fail)',
                '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
            // empty space
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            // Equipment / Operator
            $excelData[] = [
                'Equipment: ' . $dataset_data['report_head']['equipment'],
                '', '', '', '', '', '', '', '', '', '',
                'Operator name: ' . $dataset_data['report_line_items'][0]['measured_by'],
                '', ''
            ];
            $excelData[] = [
                'Table: ' . $dataset_data['report_head']['table_name'],
                '', '', '', '', '', '',
                'People Qty: ' . $dataset_data['report_head']['people_qty'],
                '',
                'Scale #: ' . $dataset_data['report_head']['scale'],
                '',
                'Pre-Op Complete: ' . $dataset_data['report_head']['pre_op_complete'],
                '', '', ''
            ];
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = [
                'Time',
                '',
                'Product Description',
                'Lot number',
                'Temp °F',
                'Allergen (If applicable)',
                'Allergen Test Result',
                'Pack Size',
                'Sample 1',
                'Sample 2',
                'Sample 3',
                'Kit Letter',
                'Qty Produced (Final)',
                'FS Initial'
            ];
            $excelData[] = [
                'Start',
                'End',
                '', '', '', '', '', '', '', '', '', '', '', ''
            ];

            // Data rows
            foreach ($dataset_data['report_line_items'] as $row) {
                $excelData[] = [
                    $row['start_time'],
                    $row['end_time'],
                    strtoupper($row['product_description']),
                    $row['lot_number'],
                    $row['temp'],
                    $row['allergen'],
                    $row['allergen_test_result'],
                    $row['pack_size'],
                    $row['sample_1'],
                    $row['sample_2'],
                    $row['sample_3'],
                    $row['kit_letter'],
                    $row['qty_produced'],
                    $row['fs_initial']
                ];
            }
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['Notes/Comments: ___________________________________________', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['Reviewed by (printed name): ________________', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['Date: ' . date('m/d/Y'), '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $excelData[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }

        return $excelData;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->getColumnDimension('A')->setWidth(11);
                $sheet->getColumnDimension('B')->setWidth(11);
                $sheet->getColumnDimension('C')->setWidth(22);
                $sheet->getColumnDimension('D')->setWidth(13);
                $sheet->getColumnDimension('E')->setWidth(9);
                $sheet->getColumnDimension('F')->setWidth(14);
                $sheet->getColumnDimension('G')->setWidth(11);
                $sheet->getColumnDimension('H')->setWidth(11);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(10);
                $sheet->getColumnDimension('K')->setWidth(10);
                $sheet->getColumnDimension('L')->setWidth(12);
                $sheet->getColumnDimension('M')->setWidth(14);
                $sheet->getColumnDimension('N')->setWidth(10);

                // Default font
                $sheet->getStyle('A1:N' . $highestRow)
                    ->getFont()->setName('Arial')->setSize(10);

                // DATE bold
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(10);

                // FOOD SAFETY
                $sheet->mergeCells('A3:N3');
                $sheet->getStyle('A3')->getFont()
                    ->setBold(true)->setSize(14)
                    ->setColor(new Color('FFD32F2F'));
                $sheet->getStyle('A3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Ingredient Portioning Form
                $sheet->mergeCells('A4:N4');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A4')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Procedure / IMPORTANT bordered box
                $sheet->mergeCells('A6:N6');
                $sheet->mergeCells('A7:N7');
                $sheet->getStyle('A6')->getAlignment()
                    ->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle('A7')->getAlignment()
                    ->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getRowDimension(6)->setRowHeight(30);
                $sheet->getRowDimension(7)->setRowHeight(30);
                $this->applyOuterBorder($sheet, 'A6:N7');

                // Info box
                $sheet->mergeCells('A9:K9');
                $sheet->mergeCells('L9:N9');
                $sheet->mergeCells('A10:G10');
                $sheet->mergeCells('H10:I10');
                $sheet->mergeCells('J10:K10');
                $sheet->mergeCells('L10:N10');

                $sheet->getStyle('A9:N9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A10:N10')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('L9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('H10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('J10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('L10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getRowDimension(9)->setRowHeight(18);
                $sheet->getRowDimension(10)->setRowHeight(18);
                $this->applyOuterBorder($sheet, 'A9:N10');

                $headerRow    = null;
                $subHeaderRow = null;
                $lastDataRow  = null;

                for ($row = 1; $row <= $highestRow; $row++) {
                    if ($sheet->getCell('A' . $row)->getValue() === 'Time') {
                        $headerRow    = $row;
                        $subHeaderRow = $row + 1;
                        break;
                    }
                }

                if ($headerRow && $subHeaderRow) {
                    $sheet->mergeCells('A' . $headerRow . ':B' . $headerRow);
                    foreach (['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'] as $col) {
                        $sheet->mergeCells($col . $headerRow . ':' . $col . $subHeaderRow);
                    }

                    $sheet->getStyle('A' . $headerRow . ':N' . $subHeaderRow)
                        ->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFD3D3D3');
                    $sheet->getStyle('A' . $headerRow . ':N' . $subHeaderRow)
                        ->getFont()->setBold(true)->setSize(10);
                    $sheet->getStyle('A' . $headerRow . ':N' . $subHeaderRow)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);

                    $sheet->getRowDimension($headerRow)->setRowHeight(20);
                    $sheet->getRowDimension($subHeaderRow)->setRowHeight(20);

                    for ($row = $subHeaderRow + 1; $row <= $highestRow; $row++) {
                        $val = (string)$sheet->getCell('A' . $row)->getValue();
                        if (strpos($val, 'Notes/Comments') !== false || $val === '') {
                            $lastDataRow = $row - 1;
                            break;
                        }
                    }
                    if (!$lastDataRow) $lastDataRow = $highestRow;

                    $sheet->getStyle('A' . $headerRow . ':N' . $lastDataRow)
                        ->getBorders()->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->setColor(new Color('FF000000'));

                    for ($row = $subHeaderRow + 1; $row <= $lastDataRow; $row++) {
                        $sheet->getStyle('A' . $row . ':N' . $row)
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                            ->setVertical(Alignment::VERTICAL_CENTER)
                            ->setWrapText(true);
                        $sheet->getStyle('C' . $row)
                            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        $sheet->getRowDimension($row)->setRowHeight(30);
                    }

                    $notesRow    = null;
                    $reviewedRow = null;
                    $dateRow     = null;

                    for ($row = $lastDataRow + 1; $row <= $highestRow; $row++) {
                        $val = (string)$sheet->getCell('A' . $row)->getValue();
                        if (strpos($val, 'Notes/Comments') !== false) $notesRow    = $row;
                        if (strpos($val, 'Reviewed by')    !== false) $reviewedRow = $row;
                        if (strpos($val, 'Date:')          !== false) $dateRow     = $row;
                    }

                    if ($notesRow && $dateRow) {
                        for ($row = $notesRow; $row <= $dateRow; $row++) {
                            $sheet->mergeCells('A' . $row . ':N' . $row);
                            $sheet->getStyle('A' . $row)
                                ->getAlignment()->setWrapText(true);
                            $sheet->getRowDimension($row)->setRowHeight(18);
                        }
                        $sheet->getRowDimension($notesRow)->setRowHeight(35);

                        if ($notesRow)    $sheet->getStyle('A' . $notesRow)->getFont()->setBold(true)->setSize(10);
                        if ($reviewedRow) $sheet->getStyle('A' . $reviewedRow)->getFont()->setBold(true)->setSize(10);
                        if ($dateRow)     $sheet->getStyle('A' . $dateRow)->getFont()->setSize(10);

                        $this->applyOuterBorder($sheet, 'A' . $notesRow . ':N' . $dateRow);
                    }
                }
            },
        ];
    }

    private function applyOuterBorder($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}