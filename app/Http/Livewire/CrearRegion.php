<?php
namespace App\Http\Livewire;

use Livewire\Component;
// use App\Models\Region; // Descomenta si vas a guardar datos

class CrearRegion extends Component
{
    public $openModal = false;
    public $nombre = '';
    public $estado = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'estado' => 'required|string',
    ];

    public function save()
    {
        $this->validate();
        // Region::create(['nombre' => $this->nombre, 'estado' => $this->estado]);
        $this->openModal = false;
        $this->reset(['nombre', 'estado']);
    }

    public function render()
    {
        return view('livewire.crear-region');
    }
}

