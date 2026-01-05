<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'brands';
    protected $fillable = ['name','status'];

    const NAME = ['MASTER STEEL','TORO REY'];


    //Relationships

    public function equipments()
    {
        return $this->hasMany( Equipment::class, 'brand_id' );
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
}
