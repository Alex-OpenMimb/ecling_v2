<?php

namespace App\Livewire\QuotationStatus;

use App\Models\QuotationStatus;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    public QuotationStatus $quotationStatus;

    #[Locked]
    public $id;

    public $name = '';
    public $description = '';
    public $status = true;

    public function mount(QuotationStatus $quotationStatus)
    {
        $this->quotationStatus = $quotationStatus?->exists ? $quotationStatus : new QuotationStatus();

        $this->id = $this->quotationStatus->id;
        $this->name = $this->quotationStatus->name ?? '';
        $this->description = $this->quotationStatus->description ?? '';
        $this->status = $this->quotationStatus->exists ? (bool) $this->quotationStatus->status : true;
    }

    public function render()
    {
        return view('livewire.quotationStatus.form');
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

        $this->quotationStatus->fill($payload);
        $this->quotationStatus->save();

        toastr()->success('El estado de cotización se ha guardado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.quotation-status.index');
    }
}
