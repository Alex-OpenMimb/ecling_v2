<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cities';
    protected $fillable = [
        'name',
        'email',
        'alias',
        'department_id',
    ];


    //Scope
    public function scopeGetCities( Builder $query,$department_id )
    {
        return $query->where('department_id',$department_id)
            ->select('id','name');
    }

    //Relationship
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
