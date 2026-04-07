<?php

namespace App\Actions\Visits;

use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Visit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrUpdateVisits
{
    use AsAction;

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
            'report' => null,
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
            'report' => $data['report'],
        ];

        return Visit::updateOrCreate(
            ['id' => $id] ,
            $payload
        );
    }
}
