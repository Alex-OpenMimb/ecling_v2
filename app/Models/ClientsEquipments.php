<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientsEquipments extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'clients_has_equipments';
    protected $fillable = [
            'serial',
            'internal_id',
            'observations',
            'status',
            'preventive_services',
            'preventive_services_first', //Flag to validate if the preventive routine has assigned already first time
            'plate_photo',
            'perimeter_photo',
            'equipment_id', //Relations many has many
            'client_id', //Relations many has many
            'location_id',
            'headquarter_id',
            'schedule_assigned',
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public function headquarter()
    {
        return  $this->belongsTo(Headquarter::class);
    }


    public function schedules()
    {
        return $this->hasOne( Schedule::class );
    }

    public function correctiveActivities()
    {
        return $this->belongsToMany(CorrectiveActivity::class);
    }

    public function schedulesServiceOrders()
    {
        return $this->hasMany(SchedulesServiceOrder::class);
    }

    public function generalReports()
    {
       return  $this->hasMany( GeneralReport::class );
    }


    ///Stters
    public function observations(): Attribute
    {
        return Attribute::make(
            set: function ( $value){
                if (preg_match('/^\s*$/', $value) ) {
                    return null;
                } else {
                    return ucfirst($value) ;
                }

            }
        );
    }

}
