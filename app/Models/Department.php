<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'email',
        'alias',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    //Scope

    public function scopeGetDepartments(Builder $query )
    {
        $query->with('cities')
            ->select('id','name');
    }


    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => mb_convert_case(mb_strtolower($value,'UTF-8'),MB_CASE_TITLE, 'UTF-8')
        );
    }
}
