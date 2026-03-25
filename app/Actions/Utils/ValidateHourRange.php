<?php

namespace App\Actions\Utils;

use Carbon\Carbon;
use InvalidArgumentException;
use Lorisleiva\Actions\Concerns\AsAction;

class ValidateHourRange
{
    use AsAction;

    /**
     * Valida que `end_hour` sea posterior a `start_hour` (mismo día).
     * Formato esperado: H:i (compatible con inputs type="time").
     *
     * @throws InvalidArgumentException
     */
    public function handle(string $startHour, string $endHour): void
    {
        $startHour = trim($startHour);
        $endHour = trim($endHour);

        if ($startHour === '' || $endHour === '') {
            throw new InvalidArgumentException('Debes indicar hora de entrada y hora de salida.');
        }

        $start = Carbon::parse('2000-01-01 '.$startHour);
        $end = Carbon::parse('2000-01-01 '.$endHour);

        if ($end->lte($start)) {
            throw new InvalidArgumentException('La hora de salida debe ser posterior a la hora de entrada.');
        }
    }
}
