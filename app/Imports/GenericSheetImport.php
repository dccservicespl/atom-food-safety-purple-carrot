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
        //Skip first 3 row
        return 4;
    }

    public function array(array $rows)
    {
        $this->data[$this->sheet_name] = $rows;
    }
}
