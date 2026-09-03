<?php

namespace App\Livewire\OrderStatus;

use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    public OrderStatus $orderStatus;

    #[Locked]
    public $id;

    public $name = '';
    public $description = '';
    public $state = true;

    public function mount(OrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus?->exists ? $orderStatus : new OrderStatus();

        if ($this->orderStatus->exists && $this->orderStatus->isLockedForEditing()) {
            $message = $this->orderStatus->isSystemDefault()
                ? 'No puedes editar este estado porque es un estado predeterminado del sistema.'
                : 'No puedes editar este estado porque está asignado a una o más órdenes de servicio.';

            toastr()->error($message, 'Acción no permitida');

            return $this->redirect(route('admin.configurations.order-status.index'));
        }

        $this->id = $this->orderStatus->id;
        $this->name = $this->orderStatus->name ?? '';
        $this->description = $this->orderStatus->description ?? '';
        $this->state = $this->orderStatus->exists ? (bool) $this->orderStatus->state : true;
    }

    public function render()
    {
        return view('livewire.orderStatus.form');
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:120',
                Rule::unique('order_status', 'name')->ignore($this->orderStatus),
                function (string $attribute, mixed $value, \Closure $fail) {
                    $exists = OrderStatus::query()
                        ->where('code', Str::slug($value, '-'))
                        ->when(
                            $this->orderStatus->exists,
                            fn ($query) => $query->whereKeyNot($this->orderStatus->getKey())
                        )
                        ->exists();

                    if ($exists) {
                        $fail('El nombre ya está registrado.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'state' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 120 caracteres.',
            'name.unique' => 'El nombre ya está registrado.',
            'description.string' => 'La descripción debe ser texto válido.',
            'description.max' => 'La descripción no puede superar los 2000 caracteres.',
            'state.boolean' => 'El estado seleccionado no es válido.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'state' => 'estado',
        ];
    }

    public function save()
    {
        if (is_string($this->name)) {
            $this->name = trim($this->name);
        }

        if (is_string($this->description)) {
            $this->description = trim($this->description);
        }

        $this->validate();

        if ($this->orderStatus->exists && $this->orderStatus->isLockedForEditing()) {
            $message = $this->orderStatus->isSystemDefault()
                ? 'No puedes guardar cambios: este estado es predeterminado del sistema.'
                : 'No puedes guardar cambios: este estado está asignado a una o más órdenes de servicio.';

            toastr()->error($message, 'Acción no permitida');

            return;
        }

        $payload = [
            'name' => $this->name,
            'code' => Str::slug($this->name, '-'),
            'description' => $this->description !== '' ? $this->description : null,
            'state' => (bool) $this->state,
        ];

        $this->orderStatus->fill($payload);
        $this->orderStatus->save();

        toastr()->success('El estado de orden se ha guardado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.order-status.index');
    }
}
