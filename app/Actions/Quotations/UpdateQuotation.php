<?php

namespace App\Actions\Quotations;

use App\Models\Quotation;
use App\Models\QuotationStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateQuotation
{
    use AsAction;

    public function handle(Quotation $quotation, int $quotationStatusId, ?string $description): Quotation
    {
        $status = QuotationStatus::query()->findOrFail($quotationStatusId);

        $quotation->update([
            'quotation_status_id' => $status->id,
            'quotation_status_name' => $status->name,
            'description' => $description !== null && trim($description) !== '' ? trim($description) : null,
        ]);

        return $quotation->fresh();
    }
}
