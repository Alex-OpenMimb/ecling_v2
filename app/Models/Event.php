<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $table = 'events';
    protected $fillable = [
        'date',
        'day',
        'start_hour',
        'end_hour',
        'activity',
        'user_id',  //Foreign key to set the created user.
        'service_order',
        'closed'
    ];



    //Relationships


    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class);
    }


    public function serviceOrders()
    {
        return $this->belongsToMany( ServiceOrder::class );
    }

    //Relationships to set the created user of  event
    public function creatorUser()
    {
        $this->belongsTo( User::class );
    }



}
