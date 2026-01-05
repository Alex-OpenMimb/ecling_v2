<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'materials';
    protected $fillable = ['material_name','status'];



    const MATERIAL = ['Gas','Lubricante'];

   //Relationships
    public function geeralReports()
    {
        return $this->belongsToMany( GeneralReport::class );
    }


    //Mutator and accessor
    protected function materialName(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }


    //scope
    public function scopeGetMaterials(Builder $query )
    {
        $query->select('id','material_name')
            ->orderBy('material_name')
            ->where('status',true);
    }
}
