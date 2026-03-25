<?php

namespace App\Actions\Utils;

use Carbon\Carbon;
use InvalidArgumentException;
use Lorisleiva\Actions\Concerns\AsAction;

class ValidateDateNotBeforeToday
{
    use AsAction;

    /**
     * Valida que la fecha indicada no sea anterior al día actual (zona horaria de la app).
     *
     * @param  string  $date  Fecha en formato compatible con Carbon (p. ej. Y-m-d desde input type="date")
     *
     * @throws InvalidArgumentException
     */
    public function handle(string $date): void
    {
        $date = trim($date);

        if ($date === '') {
            throw new InvalidArgumentException('Debes seleccionar una fecha.');
        }

        $selected = Carbon::parse($date)->startOfDay();
        $today = Carbon::today()->startOfDay();

        if ($selected->lt($today)) {
            throw new InvalidArgumentException('La fecha no puede ser anterior a hoy.');
        }
    }
}
