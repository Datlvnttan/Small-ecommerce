<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawDataImport implements ToCollection,WithHeadingRow
{
    public $rows;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $this->$rows = $rows;
    }
}
