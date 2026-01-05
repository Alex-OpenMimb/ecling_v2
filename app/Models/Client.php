<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'clients';
    protected $fillable = [
        'name',
        'slug',
        'nit',
        'status',
    ];

    //Relationships
    public function headquarters()
    {
        return $this->hasMany(Headquarter::class);
    }


    public function equipments()
    {
        return $this->belongsToMany(  Equipment::class );
    }

    public function generalReports()
    {
      return  $this->hasMany( GeneralReport::class );
    }




    //Scope

    public function scopeGetClients(Builder $query)
    {
        $query->where('status',true)->select('name','id');
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

    //Get object by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
