<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrderUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'service_orders_has_users';
    protected $fillable = [
        'service_order_id',
        'user_id',

    ];
}
