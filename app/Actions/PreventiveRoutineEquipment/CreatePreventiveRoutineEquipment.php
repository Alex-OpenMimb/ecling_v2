<?php

namespace App\Actions\PreventiveRoutineEquipment;

use App\Models\ClientsEquipments;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineEquipment;
use App\Services\Schedule\ServicesSchedule;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePreventiveRoutineEquipment
{
    use AsAction;

    /**
     * Crea un registro en preventive_routines_equipments.
     *
     * @param array $data ['equipment_client' => ClientsEquipments, 'routine_id' => int, 'custom_frequency' => ?int]
     */
    public function handle(array $data): PreventiveRoutineEquipment
    {
        $equipmentClient = $data['equipment_client'];
        $routineId = (int) $data['routine_id'];
        $customFrequency = isset($data['custom_frequency']) && $data['custom_frequency'] !== ''
            ? (int) $data['custom_frequency']
            : null;

        $equipmentId = $this->getEquipmentId($equipmentClient);
        $frequency = $this->getFrequency($routineId, $customFrequency);
        ServicesSchedule::create_schedule( $equipmentClient, $routineId, $frequency);
        return PreventiveRoutineEquipment::create([
            'equipment_id' => $equipmentId,
            'preventive_routine_id' => $routineId,
            'custom_frequency' => $frequency,
        ]);
    }

    protected function getEquipmentId(ClientsEquipments $clientEquipment): int
    {
        return (int) $clientEquipment->equipment_id;
    }

    /**
     * Si custom_frequency es null usa la frecuencia de la rutina (por id); si no, usa custom_frequency.
     */
    protected function getFrequency(int $routineId, ?int $customFrequency): int
    {
        if ($customFrequency === null) {
            $routine = PreventiveRoutine::find($routineId);

            return $routine ? (int) $routine->frequency : 0;
        }

        return $customFrequency;
    }
}
