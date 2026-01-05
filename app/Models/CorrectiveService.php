<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveService extends Model
{
    use HasFactory;

    protected $table= 'corrective_services';

    protected $fillable = [
        'status',
        'observations',
        'event_id',
        'service_order_id',
        'user_id',
    ];


    //Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo( ServiceOrder::class );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
