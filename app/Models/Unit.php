<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'units';
    protected $fillable = ['unit_name','status'];


    const UNITS = ['Lt','Mt'];

    //Relationships

    public function generalReportMaterials()
    {
        return $this->hasMany( GeneralReportMaterial::class );
    }

    //Mutator and accessor
    protected function unitName(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }


    //Scope
    public function scopeGetUnits(Builder $query )
    {
        $query->select('id','unit_name')
            ->orderBy('unit_name')
            ->where('status',true);
    }
}
