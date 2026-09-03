<?php

namespace App\Actions\Quotations;

use App\Models\QuotationVisit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateQuotationsHasVisits
{
    use AsAction;

    public function handle(int $quotationId, int $visitId): QuotationVisit
    {
        return QuotationVisit::firstOrCreate([
            'quotation_id' => $quotationId,
            'visit_id' => $visitId,
        ]);
    }
}
