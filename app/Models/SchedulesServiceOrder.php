<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchedulesServiceOrder extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'schedules_has_service_orders';
    protected $fillable = [
        'service_order_id', // Foreign key of many to many relationships
        'schedule_id',  // Foreign key of many to many relationships
        'client_has_equipment_id'
    ];



    //Relatioships
    public function clientEquipments()
    {
        return $this->belongsTo(ClientsEquipments::class);
    }
}
