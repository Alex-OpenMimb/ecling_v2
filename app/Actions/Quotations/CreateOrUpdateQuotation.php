<?php

namespace App\Actions\Quotations;

use App\Models\Quotation;
use App\Models\QuotationStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrUpdateQuotation
{
    use AsAction;

    public function handle(array $data): Quotation
    {
        $data = array_merge([
            'id' => null,
            'number' => null,
            'date' => null,
            'expiration_date' => null,
            'description' => null,
            'status' => true,
            'client_name' => null,
            'headquarter_name' => null,
            'quotation_status_name' => null,
            'quotation_status_id' => null,
            'client_id' => null,
            'headquarter_id' => null,
        ], $data);

        $id = $data['id'] ?? null;
        if (($id === null || $id === '') && empty($data['number'])) {
            throw new \InvalidArgumentException('El número de cotización es obligatorio al crear un registro.');
        }
        $payload = [
            'number' => $data['number'],
            'date' => $data['date'] ?? now(),
            'expiration_date' => $data['expiration_date'],
            'description' => $data['description'],
            'status' => (bool) $data['status'],
            'client_name' => $data['client_name'] ?? null,
            'headquarter_name' => $data['headquarter_name'] ?? null,
            'quotation_status_name' => $data['quotation_status_name'],
            'quotation_status_id' => $data['quotation_status_id'],
            'client_id' => $data['client_id'],
            'headquarter_id' => $data['headquarter_id'],
        ];

        $criteria = $id !== null && $id !== ''
            ? ['id' => (int) $id]
            : ['number' => $data['number']];

        return Quotation::updateOrCreate($criteria, $payload);
    }
}
