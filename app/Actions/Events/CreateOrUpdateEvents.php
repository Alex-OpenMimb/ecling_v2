<?php

namespace App\Actions\Events;

use App\Models\Event;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrUpdateEvents
{
    use AsAction;

    /**
     * Crea o actualiza un registro en Event.
     * Si se recibe 'id' se actualiza; si no, se crea.
     *
     * @param  array  $data  [ id?, date, day?, start_hour, end_hour, activity, user_id, service_order?, closed? ]
     * @return Event
     */
    public function handle(array $data): Event
    {
        $id = $data['id'] ?? null;
        $data = array_merge([
            'day' => null,
            'service_order' => false,
            'closed' => false,
        ], $data);

        [
            'date' => $date,
            'start_hour' => $startHour,
            'end_hour' => $endHour,
            'activity' => $activity,
            'user_id' => $userId,
            'day' => $day,
            'service_order' => $serviceOrder,
            'closed' => $closed,
        ] = $data;

        $payload = [
            'date' => $date,
            'day' => $day,
            'start_hour' => $startHour,
            'end_hour' => $endHour,
            'activity' => $activity,
            'user_id' => $userId,
            'service_order' => (bool) $serviceOrder,
            'closed' => (bool) $closed,
        ];

        return Event::updateOrCreate(
            $id ? ['id' => $id] : [],
            $payload
        );
    }
}
