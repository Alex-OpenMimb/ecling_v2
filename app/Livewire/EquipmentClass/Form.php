<?php

namespace App\Livewire\EquipmentClass;

use App\Models\EquipmentClass;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    public EquipmentClass $equipmentClass;

    #[Locked]
    public $id;

    public $name = '';
    public $status = true;

    public function mount(EquipmentClass $equipmentClass)
    {
        $this->equipmentClass = $equipmentClass?->exists ? $equipmentClass : new EquipmentClass();

        $this->id = $this->equipmentClass->id;
        $this->name = $this->equipmentClass->name ?? '';
        $this->status = $this->equipmentClass->exists ? (bool) $this->equipmentClass->status : true;
        
        // Validar si está siendo utilizado por equipos al editar
        if ($this->id && $this->equipmentClass->equipments()->exists()) {
            toastr()->error('No se puede editar la clase de equipo porque está siendo utilizada por uno o más equipos.', 'Error');
            return redirect()->route('admin.configurations.equipment-class.index');
        }
    }

    public function render()
    {
        return view('livewire.equipmentClass.form');
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:120',
                Rule::unique('equipment_classes', 'name')->ignore($this->id),
            ],
            'status' => ['boolean'],
        ];
    }

    public function save()
    {
        // Validar si está siendo utilizado por equipos al guardar
        if ($this->id && $this->equipmentClass->equipments()->exists()) {
            toastr()->error('No se puede editar la clase de equipo porque está siendo utilizada por uno o más equipos.', 'Error');
            return redirect()->route('admin.configurations.equipment-class.index');
        }
        
        $this->validate();

        $payload = [
            'name' => trim($this->name),
            'slug' => $this->generateUniqueSlug($this->name),
            'status' => (bool) $this->status,
        ];

        $this->equipmentClass->fill($payload);
        $this->equipmentClass->save();

        toastr()->success('La clase de equipo se ha guardado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.equipment-class.index');
    }

    protected function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug !== '' ? $baseSlug : Str::random(8);

        $originalSlug = $slug;
        $counter = 1;

        while (
            EquipmentClass::where('slug', $slug)
                ->when($this->id, fn ($query) => $query->where('id', '!=', $this->id))
                ->exists()
        ) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

