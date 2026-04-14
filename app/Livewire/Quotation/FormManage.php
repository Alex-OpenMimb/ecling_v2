<?php

namespace App\Livewire\Quotation;

use App\Actions\Quotations\UpdateQuotation;
use App\Models\Quotation;
use App\Models\QuotationStatus;
use Livewire\Component;

class FormManage extends Component
{
    public Quotation $quotation;

    public $quotation_status_id;

    public $description = '';

    public function mount(Quotation $quotation): void
    {
        $this->quotation = $quotation;
        $this->quotation_status_id = (string) $quotation->quotation_status_id;
        $this->description = $quotation->description ?? '';
    }

    public function render()
    {
        $quotationStatuses = QuotationStatus::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.quotation.form-manage', [
            'quotationStatuses' => $quotationStatuses,
        ]);
    }

    protected function rules(): array
    {
        return [
            'quotation_status_id' => ['required', 'exists:quotation_status,id'],
            'description' => ['nullable', 'string', 'max:65535'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        UpdateQuotation::run(
            $this->quotation,
            (int) $this->quotation_status_id,
            $this->description
        );

        toastr()->success('La cotización se actualizó correctamente.', 'Listo');

        $this->redirect(route('admin.quotations'));
    }
}
