<?php

namespace App\Actions\Events;

use App\Models\Event;
use App\Services\Event\EventServices;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEvent
{
    use AsAction;

    /**
     * Crea un Event. El día de la semana se calcula desde la fecha.
     *
     * @param  array  $data  date, start_hour, end_hour, activity?, user_id?, service_order?, closed?
     */
    public function handle(array $data): Event
    {
        $defaults = [
            'date' => null,
            'day' => null,
            'start_hour' => null,
            'end_hour' => null,
            'activity' => 'Otra',
            'user_id' => auth()->id(),
            'service_order' => false,
            'closed' => false,
        ];

        $merged = array_merge($defaults, $data);

        if (! empty($merged['date'])) {
            $dateStr = $merged['date'] instanceof \DateTimeInterface
                ? $merged['date']->format('Y-m-d')
                : (string) $merged['date'];
            $merged['day'] = EventServices::get_day_week($dateStr);
        }

        return Event::create([
            'date' => $merged['date'],
            'day' => $merged['day'],
            'start_hour' => $merged['start_hour'],
            'end_hour' => $merged['end_hour'],
            'activity' => $merged['activity'],
            'user_id' => (int) $merged['user_id'],
            'service_order' => (bool) $merged['service_order'],
            'closed' => (bool) $merged['closed'],
        ]);
    }
}
