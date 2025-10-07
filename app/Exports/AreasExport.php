<?php

namespace App\Exports;

use App\Models\Area;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AreasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Area::select('id_area', 'nombre', 'estatus')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Área',
            'Estatus',
        ];
    }
}
