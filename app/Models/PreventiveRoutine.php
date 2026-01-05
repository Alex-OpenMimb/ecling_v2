<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreventiveRoutine extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'preventive_routines';
    protected $fillable = [
        'name',
        'frequency',
        'status',
        //'schedule_assignment',
        'equipment_class_id',
    ];


    //Relationships

    public function equipmentClass()
    {
        return $this->belongsTo(EquipmentClass::class);
    }


    public function preventiveActivities()
    {
        return $this->belongsToMany( PreventiveActivity::class );
    }


    public function equipments()
    {
        return $this->belongsToMany( Equipment::class );
    }

    public function schedules()
    {
        return $this->hasMany( Schedule::class );
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



    //Scope

    public function scopeGetPreventiveRoutineById(Builder $query,$id)
    {
        $query->where('id',$id)->select('id','name');

    }
}
