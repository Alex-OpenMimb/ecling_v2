<?php

namespace App\Actions\Visits;

use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Visit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrUpdateVisits
{
    use AsAction;

    /**
     * Crea o actualiza un registro en Visit.
     * Si se recibe 'id' se actualiza; si no, se crea.
     * client_name y headquarter_name se resuelven desde client_id/headquarter_id si no se envían.
     * client_id y headquarter_id pueden ser null.
     *
     * @param  array  $data  [ id?, event_id, client_id?, headquarter_id?, visit_reason_id, observations?, status?, client_name?, headquarter_name? ]
     */
    public function handle(array $data): Visit
    {
        $data = array_merge([
            'id' => null,
            'event_id' => null,
            'client_id' => null,
            'headquarter_id' => null,
            'visit_reason_id' => null,
            'observations' => null,
            'status' => true,
            'client_name' => null,
            'headquarter_name' => null,
        ], $data);

        $clientId = $data['client_id'] !== null && $data['client_id'] !== ''
            ? (int) $data['client_id']
            : null;
        $headquarterId = $data['headquarter_id'] !== null && $data['headquarter_id'] !== ''
            ? (int) $data['headquarter_id']
            : null;

        $resolvedClientName = $data['client_name'] ?? Client::find($clientId)?->name;
        $resolvedHeadquarterName = $data['headquarter_name'] ?? Headquarter::find($headquarterId)?->name;

        $id = $data['id'] ?? null;

        $payload = [
            'event_id' => (int) $data['event_id'],
            'client_id' => $clientId,
            'headquarter_id' => $headquarterId,
            'visit_reason_id' => (int) $data['visit_reason_id'],
            'observations' => $data['observations'],
            'status' => (bool) $data['status'],
            'client_name' => $resolvedClientName,
            'headquarter_name' => $resolvedHeadquarterName,
        ];

        return Visit::updateOrCreate(
            ['id' => $id] ,
            $payload
        );
    }
}
