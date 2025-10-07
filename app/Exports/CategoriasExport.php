<?php

namespace App\Exports;

use App\Models\Categoria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriasExport implements FromCollection, WithHeadings
{
    // Recolecta los datos que se van a exportar
    public function collection()
    {
        return Categoria::select('id_categoria', 'nombre', 'estatus')->get();
    }

    // Encabezados de la hoja
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Estatus',
        ];
    }
}
