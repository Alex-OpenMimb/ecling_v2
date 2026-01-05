<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'addresses';
    protected $fillable = [
        'nomenclature_main',
        'number_main',
        'nomenclature_second',
        'number_second',
        'number',
        'observations',
        'city_id',
    ];

    //Relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }
}
