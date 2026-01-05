<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'locations';
    protected $fillable = [
        'name',
        'status',
    ];

    const LOCATIONS = ['Bodega','Cocina','Salón de eventos'];


    //Relationships

    public function clientEquipment()
    {
         return $this->hasMany( ClientsEquipments::class );
    }





    //Mutator and accessor
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }


    //scope

    public function scopeGetLocations(Builder $query )
    {
        $query->select('id','name')
            ->orderBy('name')
            ->where('status',true);
    }

    public function scopeGetLocationById(Builder $query, $location_id )
    {
        $query->where('id', $location_id)->select('name');
    }
}
