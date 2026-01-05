<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_photo_id',
        'model_id',
        'model_type',
        'path',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function titlePhoto()
    {
        return $this->belongsTo(TitlePhoto::class);
    }
}
