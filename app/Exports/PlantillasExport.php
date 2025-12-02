<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlantillasExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Item::where('id_checklist', $this->id)
            ->select([
                
                'nombre_item',
                'puntuacion',
                'plan_accion',
                'obligatorio',
                'tipo_evidencias',
                'tipo_item'
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            
            'Nombre de la Pregunta',
            'Puntuación',
            'Plan de Acción',
            'Obligatorio',
            'Tipo de Evidencia',
            'Tipo de Item'
        ];
    }
}
