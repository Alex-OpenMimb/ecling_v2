<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $table = 'visits';
    protected $fillable = [
        'client_name',
        'headquarter_name',
        'observations',
        'report',
        'status',
        'event_id',
        'client_id',
        'visit_reason_id',
        'headquarter_id',
    ];


    public function event()
    {
        return $this->belongsTo( Event::class );
    }


    public function client()
    {
        return $this->belongsTo( Client::class );
    }


    public function headquarter()
    {
        return $this->belongsTo( Headquarter::class );
    }

    public function visitReason()
    {
        return $this->belongsTo( VisitReason::class, 'visit_reason_id' );
    }

    public function quotations()
    {
        return $this->belongsToMany(
            Quotation::class,
            'quotations_has_visits',
            'visit_id',
            'quotation_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'visits_users', 'visit_id', 'user_id');
    }
}
