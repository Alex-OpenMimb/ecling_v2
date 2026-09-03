<?php

namespace App\Livewire\VisitReason;

use App\Models\VisitReason;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    public VisitReason $visitReason;

    #[Locked]
    public $id;

    public $name = '';
    public $description = '';
    public $status = true;

    public function mount(VisitReason $visitReason)
    {
        $this->visitReason = $visitReason?->exists ? $visitReason : new VisitReason();

        $this->id = $this->visitReason->id;
        $this->name = $this->visitReason->name ?? '';
        $this->description = $this->visitReason->description ?? '';
        $this->status = $this->visitReason->exists ? (bool) $this->visitReason->status : true;
    }

    public function render()
    {
        return view('livewire.visitReason.form');
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:120',
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['boolean'],
        ];
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'name' => trim($this->name),
            'description' => $this->description !== '' ? trim($this->description) : null,
            'status' => (bool) $this->status,
        ];

        $this->visitReason->fill($payload);
        $this->visitReason->save();

        toastr()->success('La razón se ha guardado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.visit-reasons.index');
    }
}

