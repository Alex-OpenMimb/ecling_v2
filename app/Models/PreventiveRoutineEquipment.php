<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreventiveRoutineEquipment extends Model
{
    use HasFactory;


    //It Many to many table
    protected $table = 'preventive_routines_equipments';
    protected $fillable = [
        'equipment_id',
        'preventive_routine_id',
        'custom_frequency'

    ];
}
