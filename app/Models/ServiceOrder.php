<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'service_orders';
    protected $fillable = [
        'serial',
        'status',
        'observations_status',
        'observations',
        'activity',
        'rejected_by', /// foreign key to set the  user who reject the service order.
        'user_id', // foreign key to set the created user of service order.

    ];



    //Relationships

    public function users()
    {
        return  $this->belongsToMany( User::class );
    }


    // Relationships to set the created user of service order.
    public function orderUser()
    {
       return $this->belongsTo( User::class );
    }


    public function rejectUser()
    {
      return  $this->belongsTo( User::class,'rejected_by' );
    }
    public function schedules()
    {
       return $this->belongsToMany( Schedule::class );
    }

    public function generalReports()
    {
       return $this->hasMany( GeneralReport::class );
    }

    public function events()
    {
        return $this->belongsToMany(Event::class   );
    }


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
