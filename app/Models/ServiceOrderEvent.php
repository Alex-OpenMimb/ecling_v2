<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrderEvent extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'service_orders_has_events';
    protected $fillable = [
        'service_order_id',
        'event_id',

    ];

}
