<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
    use HasFactory;

    protected $table = 'schedules_has_events';
    protected $fillable = [
        'event_id',
        'schedule_id',
        'service_order_id'
    ];
}
