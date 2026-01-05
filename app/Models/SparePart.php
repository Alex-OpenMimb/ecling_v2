<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'spare_parts';
    protected $fillable = ['spare_part_name','status'];


    const SPARE = [
        'Tuerca',
        'Tarjeta'
    ];


    //Relationships
    public function geeralReports()
    {
        return $this->belongsToMany( GeneralReport::class );
    }

    //Mutator and accessor
    protected function sparePartName(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    //Scope

    public function scopeGetSpareParts(Builder $query )
    {
        $query->select('id','spare_part_name')
            ->orderBy('spare_part_name')
            ->where('status',true);
    }
}
