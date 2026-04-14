<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Headquarter extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'headquarters';
    protected $fillable = [
        'name',
        'slug',
        'main',
        'contact_name',
        'phone_1',
        'phone_2',
        'client_id',
        'address_id',
        'email',
    ];

    const LOCATION = ['Sala','Cocina','Bodega','Salón de eventos'];

    //Relationships

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function clientEquipment()
    {
        return $this->hasMany( ClientsEquipments::class );
    }

    public function generalReports()
    {
       return $this->hasMany( GeneralReport::class );
    }

    public function quotation()
    {
        return $this->hasMany(Quotation::class);
    }


    //Mutator and accessor
    protected function contactName(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucwords($value) : $value;
            },
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    //Scopes
    public function scopeGetHeadquarterByClient(Builder $query, $id)
    {
        $query->where('client_id',$id)->select('name','id');
    }

    //Get object by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

}
