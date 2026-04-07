<?php

namespace App\Actions\Utils;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateNextQuotationNumber
{
    use AsAction;

    /**
     * Obtiene el mayor `number` numérico en `quotations` y devuelve el siguiente consecutivo
     * en 7 dígitos (p. ej. 0000001 si no hay registros).
     */
    public function handle(): string
    {
        $max = DB::table('quotations')
            ->selectRaw('MAX(CAST(`number` AS UNSIGNED)) as max_num')
            ->value('max_num');

        $next = (int) ($max ?? 0) + 1;

        return str_pad((string) $next, 7, '0', STR_PAD_LEFT);
    }
}
