<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ampere extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'amperes';
    protected $fillable = ['amperage_measurement','status','unit'];

    const AMPERE = [10,20];


    //Relationships
    public function equipments()
    {
        $this->hasMany( Equipment::class );
    }


}
