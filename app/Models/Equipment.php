<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'equipments';
    protected $fillable = [
        'description',
        'name',
        'slug',
        'status',
        'asset_assignment',
        'routine_assignment',
        'equipment_model_id',
        'equipment_class_id',
        'brand_id',
        'volt_id',
        'ampere_id',
    ];



    // Data to seeders

    const EQUIPMENTS = [
        [
            'name'=>'Aire de Ventana',
            'slug'=>'aire_de_ventana',
            'equipment_model_id'=> 6,
            'equipment_class_id'=> 1,
            'brand_id'=> 1,
            'volt_id'=> 1,
            'ampere_id'=> 2,
        ],
        [
            'name'=>'Mini Split',
            'slug'=>'mini_split',
            'equipment_model_id'=> 1,
            'equipment_class_id'=> 1,
            'brand_id'=> 1,
            'volt_id'=> 1,
            'ampere_id'=> 1,
        ],
        [
            'name'=>'Aire Convencional',
            'slug'=>'aire_convencional',
            'equipment_model_id'=> 3,
            'equipment_class_id'=> 1,
            'brand_id'=> 2,
            'volt_id'=> 1,
            'ampere_id'=> 1,
        ],
    ];


    public function equipmentClass()
    {
        return $this->belongsTo( EquipmentClass::class );
    }


    public function equipmentModel()
    {
        return $this->belongsTo( EquipmentModel::class );
    }

    public function brand()
    {
        return $this->belongsTo( Brand::class );
    }





    public function volts()
    {
        return $this->belongsTo( Volt::class );
    }

    public function amperes()
    {
        return $this->belongsTo( Ampere::class );
    }


    public function clients()
    {
        return  $this->belongsToMany( Client::class );
    }

    public function preventiveRoutines()
    {
        return $this->belongsToMany( PreventiveRoutine::class );
    }

    //Setters

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    public function description(): Attribute
    {
        return Attribute::make(
            set: function ( $value){
                if (preg_match('/^\s*$/', $value) ) {
                    return null;
                } else {
                    return ucfirst($value) ;
                }

            }
        );
    }

    //Scope

    public function scopeGetEquipments(Builder $query)
    {
        $query->select('name','id');
    }

    //Get object by slug
//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }

}
