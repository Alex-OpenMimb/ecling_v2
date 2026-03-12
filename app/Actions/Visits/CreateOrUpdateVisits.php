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
     * client_name y headquarter_name se pueden resolver desde client_id/headquarter_id si no se envían.
     *
     * @param  array  $data  [ id?, event_id, client_id, headquarter_id, observations?, report?, status?, client_name?, headquarter_name? ]
     * @return Visit
     */
    public function handle(array $data): Visit
    {
        [
            'event_id' => $eventId,
            'client_id' => $clientId,
            'headquarter_id' => $headquarterId,
            'observations' => $observations,
            'report' => $report,
            'status' => $status,
            'client_name' => $clientName,
            'headquarter_name' => $headquarterName,
        ] = array_merge([
            'observations' => null,
            'report' => null,
            'status' => false,
            'client_name' => null,
            'headquarter_name' => null,
        ], $data);

        $resolvedClientName = $clientName ?? Client::find($clientId)?->name;
        $resolvedHeadquarterName = $headquarterName ?? Headquarter::find($headquarterId)?->name;

        $id = $data['id'] ?? null;

        $payload = [
            'event_id' => $eventId,
            'client_id' => $clientId,
            'headquarter_id' => $headquarterId,
            'observations' => $observations,
            'report' => $report,
            'status' => (bool) $status,
            'client_name' => $resolvedClientName,
            'headquarter_name' => $resolvedHeadquarterName,
        ];

        return Visit::updateOrCreate(
            $id ? ['id' => $id] : [],
            $payload
        );
    }
}
