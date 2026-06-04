<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GenericSheetImport implements ToArray, WithStartRow
{
    private $sheet_name;
    private $data;

    public function __construct($sheet_name, &$data)
    {
        $this->sheet_name = $sheet_name;
        $this->data = &$data;
    }

    public function startRow(): int
    {
        return 1;
    }

    public function array(array $rows)
    {
        $cleaned = [];

        $titleRow = null;
        if (!empty($rows[1])) {
            foreach ($rows[1] as $cell) {
                if (!empty($cell) && is_string($cell) && stripos($cell, 'Week') !== false) {
                    $titleRow = trim($cell);
                    break;
                }
            }
        }

        $headerRow = null;
        if (!empty($rows[2])) {
            $headerRow = $rows[2];
        }

        $colMap = [];
        if ($headerRow) {
            foreach ($headerRow as $idx => $cell) {
                if (!is_string($cell) || trim($cell) === '') {
                    continue;
                }
                $colMap[trim($cell)] = $idx;
            }
        }

        // Parse week info from title row
        $weekInfo = $this->parseWeekInfo($titleRow);
        if (!$weekInfo) {
            $weekInfo = [
                'from_date' => now()->toDateString(),
                'to_date'   => now()->addDays(6)->toDateString(),
            ];
        }

        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];

            $cleaned_row = [];
            foreach ($row as $cell) {
                if (is_string($cell) && str_starts_with(trim($cell), '=')) {
                    $cleaned_row[] = null;
                    continue;
                }
                $cleaned_row[] = ($cell === '' || $cell === null) ? null : $cell;
            }

            // Skip rows where all cells are null
            $non_empty = array_filter($cleaned_row, fn($cell) => $cell !== null);
            if (empty($non_empty)) {
                continue;
            }

            // Map by header name
            $mapped = [
                'from_date'         => $weekInfo['from_date'],
                'to_date'           => $weekInfo['to_date'],
                'scheduled_day'     => $this->readByHeader($cleaned_row, $colMap, 'Scheduled Day'),
                'letter'            => $this->readByHeader($cleaned_row, $colMap, 'Letter'),
                'component_details' => $this->readByHeader($cleaned_row, $colMap, 'Component Details'),
                'label'             => $this->readByHeader($cleaned_row, $colMap, 'Label'),
                'weight'            => $this->readByHeader($cleaned_row, $colMap, 'Weight'),
                'quantity'          => $this->readByHeader($cleaned_row, $colMap, 'Quantity'),
                'film_size'         => $this->readByHeader($cleaned_row, $colMap, 'Film Size'),
                'allergen'          => $this->readByHeader($cleaned_row, $colMap, 'Allergen'),
                'packaging'         => $this->readByHeader($cleaned_row, $colMap, 'Packaging'),
                '95_percent'        => $this->readByHeader($cleaned_row, $colMap, '95 %'),
            ];

            // Skip if mapped row is empty
            $nonEmptyMapped = array_filter($mapped, fn($v) => $v !== null && $v !== '');
            if (empty($nonEmptyMapped)) {
                continue;
            }

            // Skip if scheduled_day is null — row is unusable
            if (empty($mapped['scheduled_day'])) {
                continue;
            }

            // Convert scheduled_day name to actual date
            if (!empty($mapped['scheduled_day'])) {
                $dayName = strtoupper(trim($mapped['scheduled_day']));

                $dayMap = [
                    'MONDAY'    => 0,
                    'TUESDAY'   => 1,
                    'WEDNESDAY' => 2,
                    'THURSDAY'  => 3,
                    'FRIDAY'    => 4,
                    'SATURDAY'  => 5,
                    'SUNDAY'    => 6,
                ];

                if (isset($dayMap[$dayName])) {
                    $fromDate                = \Carbon\Carbon::parse($weekInfo['from_date']);
                    $mapped['scheduled_day'] = $fromDate->copy()->addDays($dayMap[$dayName])->toDateString();
                } else {
                    // Not a valid day name, skip this row
                    continue;
                }
            }

            $cleaned[] = $mapped;
        }

        $this->data[$this->sheet_name] = $cleaned;
    }

    protected function parseWeekInfo(?string $title): ?array
    {
        if (empty($title)) {
            return null;
        }

        if (preg_match('/Week\s+(\d+\.\d+)/i', $title, $matches)) {
            $week  = $matches[1];
            $parts = explode('.', $week);
            $month = (int) $parts[0];
            $day   = (int) $parts[1];

            if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                $year     = now()->year;
                $fromDate = \Carbon\Carbon::createFromDate($year, $month, $day);
                $toDate   = $fromDate->copy()->addDays(6);

                return [
                    'week'      => $week,
                    'from_date' => $fromDate->toDateString(),
                    'to_date'   => $toDate->toDateString(),
                ];
            }
        }

        return null;
    }

    protected function readByHeader(array $row, array $colMap, string $header): ?string
    {
        $key = trim($header);

        if (!isset($colMap[$key])) {
            return null;
        }

        $idx = $colMap[$key];

        if (!isset($row[$idx])) {
            return null;
        }

        $value = $row[$idx];
        if ($value === '' || $value === null) {
            return null;
        }

        return (string) $value;
    }
}
