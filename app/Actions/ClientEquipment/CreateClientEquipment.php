<?php

namespace App\Actions\ClientEquipment;

use App\Actions\Helpers\StorePhoto;
use App\Helper\HandelSerial;
use App\Models\ClientsEquipments;
use App\Models\Equipment;
use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientEquipment
{
    use AsAction;

    public function handle(Equipment $equipment, array $data)
    {
        // Crear o buscar Location
        $location = $this->createOrFindLocation($data['location']);

        // Crear el registro en clients_has_equipments
        $clientEquipment = $this->createClientEquipment($equipment, $location, $data);

        // Guardar las fotos si existen
        $this->storePhotos($clientEquipment, $data);

        return [
            'client_equipment' => $clientEquipment,
            'location' => $location,
        ];
    }

    protected function createOrFindLocation(string $locationName): Location
    {
        return Location::firstOrCreate(
            ['name' => $locationName],
            ['status' => true]
        );
    }

    protected function createClientEquipment(Equipment $equipment, Location $location, array $data): ClientsEquipments
    {
        [
            'observations' => $observations,
            'client_id' => $clientId,
            'headquarter_id' => $headquarterId,
            'equipment_class_id' => $equipmentClassId,
        ] = $data;

        return ClientsEquipments::create([
            'internal_id' => HandelSerial::build_equipment_serial('clients_has_equipments', $equipmentClassId),
            'observations' => $observations,
            'status' => true,
            'preventive_services' => false,
            'preventive_services_first' => false,
            'equipment_id' => $equipment->id,
            'client_id' => $clientId,
            'location_id' => $location->id,
            'headquarter_id' => $headquarterId,
            'schedule_assigned' => false,
        ]);
    }

    protected function storePhotos(ClientsEquipments $clientEquipment, array $data): void
    {
        // Guardar foto 1 (plate_photo) si existe
        if (isset($data['plate_photo']) && $data['plate_photo'] instanceof UploadedFile) {
            $titlePhotoId = !empty($data['photo1_title_photo_id']) ? (int) $data['photo1_title_photo_id'] : null;
            $photoData = [
                'file' => $data['plate_photo'],
                'title_photo_id' => $titlePhotoId,
                'model' => $clientEquipment,
                'base_path' => 'image/client_equipment/plate_photo',
            ];
            StorePhoto::run($photoData);
        }

        // Guardar foto 2 (perimeter_photo) si existe
        if (isset($data['perimeter_photo']) && $data['perimeter_photo'] instanceof UploadedFile) {
            $titlePhotoId = !empty($data['photo2_title_photo_id']) ? (int) $data['photo2_title_photo_id'] : null;
            $photoData = [
                'file' => $data['perimeter_photo'],
                'title_photo_id' => $titlePhotoId,
                'model' => $clientEquipment,
                'base_path' => 'image/client_equipment/perimeter_photo',
            ];
            StorePhoto::run($photoData);
        }
    }
}
