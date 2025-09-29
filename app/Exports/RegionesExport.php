<?php

namespace App\Exports;

use App\Models\Region;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegionesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Region::all();
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Estado', 'Estatus'];
    }
}


