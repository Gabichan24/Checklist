<?php 

namespace App\Exports;

use App\Models\Zona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ZonasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Incluimos también el nombre de la región si hay relación definida
        return Zona::with('region')
            ->get()
            ->map(function ($zona) {
                return [
                    'ID' => $zona->id_zona,
                    'Nombre' => $zona->nombre,
                    'Región' => $zona->region ? $zona->region->nombre : 'Sin región',
                    'Estatus' => $zona->estatus == 1 ? 'Activo' : 'Inactivo',
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Región', 'Estatus'];
    }
}
