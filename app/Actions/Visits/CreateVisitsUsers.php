<?php

namespace App\Actions\Visits;

use App\Models\VisitUser;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateVisitsUsers
{
    use AsAction;

    /**
     * Crea asociaciones en `visits_users` para un `visit_id`.
     *
     * @param int   $visitId
     * @param array $userIds
     */
    public function handle(int $visitId, array $userIds): void
    {
        $userIds = array_values(array_unique(array_filter(array_map(static function ($id) {
            return $id === '' ? null : (int) $id;
        }, $userIds))));

        foreach ($userIds as $userId) {
            VisitUser::firstOrCreate([
                'visit_id' => $visitId,
                'user_id' => $userId,
            ]);
        }
    }
}

