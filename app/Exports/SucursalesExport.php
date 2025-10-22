<?php

namespace App\Exports;

use App\Models\Sucursal;
use App\Models\Area;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SucursalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sucursal::with('zona') // solo zona
            ->select(
                'id_sucursal',
                'nombre',
                'identificador',
                'codigo_postal',
                'direccion',
                'direccion_maps',
                'latitud',
                'longitud',
                'radio',
                'id_zona',
                'id_area',
                'estatus' // lo dejamos para usarlo al final
            )
            ->get()
            ->map(function ($sucursal) {
                // Obtener nombres de áreas desde id_area
                $areaNombres = [];
                if ($sucursal->id_area) {
                    $ids = explode(',', $sucursal->id_area);
                    $areaNombres = Area::whereIn('id_area', $ids)->pluck('nombre')->toArray();
                }

                return [
                    'id_sucursal' => $sucursal->id_sucursal,
                    'nombre' => $sucursal->nombre,
                    'Clave' => $sucursal->identificador,
                    'codigo_postal' => $sucursal->codigo_postal,
                    'direccion' => $sucursal->direccion,
                    'direccion_maps' => $sucursal->direccion_maps,
                    'latitud' => $sucursal->latitud,
                    'longitud' => $sucursal->longitud,
                    'radio' => $sucursal->radio,
                    'zona' => $sucursal->zona?->nombre ?? 'Sin zona',
                    'areas' => implode(', ', $areaNombres), // nombres de áreas
                    'estatus' => $sucursal->estatus, // estatus al final
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Clave',
            'Código Postal',
            'Dirección',
            'Dirección Maps',
            'Latitud',
            'Longitud',
            'Radio (mts)',
            'Zona',
            'Áreas',
            'Estatus', // estatus al final
        ];
    }
}
