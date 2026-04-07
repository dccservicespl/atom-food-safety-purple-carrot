<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class PortioningMultiSheetImport implements WithMultipleSheets, SkipsUnknownSheets
{
    protected $data = [];
    protected $resolved_sheets = [];

    public function setResolvedSheets(array $resolved_sheets): void
    {
        $this->resolved_sheets = $resolved_sheets;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->resolved_sheets as $actual_name => $base_name) {
            $sheets[$actual_name] = new GenericSheetImport($base_name, $this->data);
        }

        return $sheets;
    }

    public function onUnknownSheet($sheetName)
    {
        
    }

    public function getData(): array
    {
        return $this->data;
    }
}