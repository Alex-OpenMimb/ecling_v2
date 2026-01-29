<?php

namespace App\Actions\Helpers;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPhotos
{
    use AsAction;

    /**
     * Obtiene los paths de las fotos asociadas a un modelo específico
     *
     * @param array $data Array con 'model' (Model|string) y 'id' (int|null)
     * @return array Array de paths de las fotos
     */
    public function handle(array $data): array
    {
        [
            'model' => $model,
            'id' => $modelId,
        ] = $data + ['id' => null];

        // Si no se proporciona el id, extraerlo del modelo
        if ($modelId === null && $model instanceof Model) {
            $modelId = $model->id;
        }

        // Validar que tenemos el id necesario
        if ($modelId === null) {
            return [];
        }

        // Obtener el tipo de modelo
        $modelType = $model instanceof Model 
            ? get_class($model) 
            : $model;

        // Consultar las fotos que coincidan con el modelo y el id
        $photos = Photo::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->get();

        // Retornar solo los paths, filtrando valores nulos
        return $photos->pluck('path')
            ->filter()
            ->values()
            ->toArray();
    }
}
