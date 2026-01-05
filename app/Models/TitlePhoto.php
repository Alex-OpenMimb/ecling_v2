<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitlePhoto extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title','status','slug'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    //Get object by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
