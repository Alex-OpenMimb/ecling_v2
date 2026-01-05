<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreventiveRoutineActivity extends Model
{
    use HasFactory;


    protected $table = 'preventive_routines_activities';
    protected $fillable = [
        'preventive_activity_id',
        'preventive_routine_id'

    ];
}
