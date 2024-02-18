<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return new Collection($this->users);
    }

    public function headings(): array
    {
        return [
            'SL',
            'Device Id',
            'User Id',
            'Name',
            'Role',
            'Password',
            'Card No',
        ];
    }
}
