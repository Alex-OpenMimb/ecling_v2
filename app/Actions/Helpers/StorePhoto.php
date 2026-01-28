<?php

namespace App\Actions\Helpers;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePhoto
{
    use AsAction;

    public function handle(array $data): Photo
    {
        [
            'file' => $file,
            'title_photo_id' => $titlePhotoId,
            'model' => $model,
            'base_path' => $basePath,
        ] = $data;

        // Guardar el archivo
        $storedPath = $this->storeFile($file, $basePath, $model->id);

        // Crear el registro en la tabla photos
        return $this->createPhoto($storedPath, $titlePhotoId, $model);
    }

    protected function storeFile(UploadedFile $file, string $basePath, int $modelId): string
    {
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $filename = 'photo_' . Str::uuid() . '.' . $extension;
        $fullPath = $basePath . '/' . $modelId;

        return $file->storeAs($fullPath, $filename, 'public');
    }

    protected function createPhoto(string $path, ?int $titlePhotoId, Model $model): Photo
    {
        return Photo::create([
            'path' => $path,
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'title_photo_id' => $titlePhotoId,
        ]);
    }
}
