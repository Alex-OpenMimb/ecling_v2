<?php

namespace App\Console\Commands;

use App\Models\ClientsEquipments;
use App\Services\Schedule\ServicesSchedule;
use Illuminate\Console\Command;

class ScheduleService extends Command
{
    protected $signature = 'schedule:service {id : ID del equipo del cliente (clients_has_equipments)}';

    protected $description = 'Crea el cronograma para un equipo del cliente usando ServicesSchedule::create_schedule';

    public function handle(): int
    {
        $id = $this->argument('id');
        if (! is_numeric($id)) {
            $this->error('El ID debe ser numérico.');
            return self::FAILURE;
        }

        $clientEquipment = ClientsEquipments::find($id);
        if (! $clientEquipment) {
            $this->error("No existe el equipo del cliente con ID {$id}.");
            return self::FAILURE;
        }

        ServicesSchedule::create_schedule($clientEquipment);
        $this->info("Cronograma creado para el equipo del cliente ID {$id}.");
        return self::SUCCESS;
    }
}
