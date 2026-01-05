<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentClass extends Model
{
    use HasFactory;

    protected $table = 'equipment_classes';
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    const TYPE = ['Gas','Eléctrico','Refrigeración'];


    //Relationships

    public function equipments()
    {
         return $this->hasMany( Equipment::class );
    }

    public function equipmentModel()
    {
        return $this->hasMany( EquipmentModel::class );
    }

    public function prevntiveActivities()
    {
        return $this->hasMany(PreventiveActivity::class);
    }

    public function prevntiveRoutine()
    {
        return $this->hasMany(PreventiveRoutine::class);
    }


    public function correctiveActivities()
    {
        return $this->hasMany(CorrectiveActivity::class);
    }

    public function clientEquipmentCorrective()
    {
        return $this->hasMany(ClientsEquipmentsCorrective::class);
    }

    public function generalReports()
    {
        return  $this->hasMany( GeneralReport::class );
    }




    //Setter
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    //Scope
    public function scopeGetEquipmentClasses(Builder $query  )
    {
        $query->select('id','name');
    }

    public function scopeGetEquipmentClassesById(Builder $query,$id  )
    {
        $query->where('id',$id)->select('id','name','slug');
    }

    public function scopeGetEquipmentClassesBySlug(Builder $query,$slug  )
    {
        $query->where('slug',$slug)->select('id','name');
    }

    //Get object by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
