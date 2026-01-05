<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'schedules';
    protected $fillable = [
        'active',
        'service_order',
        'last_date',
        'next_date',
        'days',
        'status',
        'frequency',
        'observations',
        'preventive_routine_id',
        'client_has_equipment_id',
        'equipment_id_flag' // Is a file to check if the equipment was scheduled
    ];

    //Relationships
    public function client_equipment()
    {
        return $this->belongsTo(ClientsEquipments::class,'client_has_equipment_id');
    }

    public function preventive_routine()
    {
        return $this->belongsTo(PreventiveRoutine::class);
    }

    public function serviceOrders()
    {
        return $this->belongsToMany(ServiceOrder::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }



    //Getters




    public function observations(): Attribute
    {
        return Attribute::make(
            set: function ( $value){
                if (preg_match('/^\s*$/', $value) ) {
                    return null;
                }else {
                    return $value ;
                }

            }
        );
    }
}
