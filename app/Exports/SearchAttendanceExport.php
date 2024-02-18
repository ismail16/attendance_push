<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SearchAttendanceExport implements FromCollection, WithHeadings
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return new Collection($this->attendances);
    }

    public function headings(): array
    {
        return [
            'ID',
            'API Key',
            'Device ID',
            'User ID',
            'Date Time',
            'Status',
        ];
    }
}
