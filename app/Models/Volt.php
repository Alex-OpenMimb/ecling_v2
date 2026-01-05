<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volt extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'volts';
    protected $fillable = ['volt_measurement','status','unit'];


    const VOLT =[110,220];

    //Relationships

    public function equipments()
    {
        $this->hasMany( Equipment::class );
    }


}
