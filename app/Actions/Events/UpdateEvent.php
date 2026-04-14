<?php

namespace App\Actions\Events;

use App\Models\Event;
use App\Services\Event\EventServices;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEvent
{
    use AsAction;

    /**
     * Actualiza un Event existente. Los campos no enviados se conservan.
     * Si hay fecha, el día de la semana se recalcula.
     *
     * @param  array  $data  date?, start_hour?, end_hour?, activity?, user_id?, service_order?, closed?
     */
    public function handle(int $eventId, array $data): Event
    {
        $event = Event::findOrFail($eventId);

        $base = [
            'date' => $event->date,
            'day' => $event->day,
            'start_hour' => $event->start_hour,
            'end_hour' => $event->end_hour,
            'activity' => $event->activity,
            'user_id' => $event->user_id,
            'service_order' => (bool) $event->service_order,
            'closed' => (bool) $event->closed,
        ];

        $merged = array_merge($base, $data);

        if (! empty($merged['date'])) {
            $dateStr = $merged['date'] instanceof \DateTimeInterface
                ? $merged['date']->format('Y-m-d')
                : (string) $merged['date'];
            $merged['day'] = EventServices::get_day_week($dateStr);
        }

        $event->update([
            'date' => $merged['date'],
            'day' => $merged['day'],
            'start_hour' => $merged['start_hour'],
            'end_hour' => $merged['end_hour'],
            'activity' => $merged['activity'],
            'user_id' => (int) $merged['user_id'],
            'service_order' => (bool) $merged['service_order'],
            'closed' => (bool) $merged['closed'],
        ]);

        return $event->fresh();
    }
}
