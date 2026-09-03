<?php

namespace App\Actions\Visits;

use App\Models\VisitUser;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateVisitsUsers
{
    use AsAction;

    /**
     * Actualiza asociaciones en `visits_users` para un `visit_id`.
     * Reemplaza la lista completa de usuarios.
     *
     * @param int   $visitId
     * @param array $userIds
     */
    public function handle(int $visitId, array $userIds): void
    {
        VisitUser::where('visit_id', $visitId)->delete();

        (new CreateVisitsUsers())->handle($visitId, $userIds);
    }
}

