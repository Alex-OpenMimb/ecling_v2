<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'number',
        'date',
        'expiration_date',
        'description',
        'status',
        'client_name',
        'headquarter_name',
        'quotation_status_name',
        'quotation_status_id',
        'client_id',
        'headquarter_id',
    ];

    public function quotation_status()
    {
        return $this->belongsTo( QuotationStatus::class, 'quotation_status_id' );
    }

    public function client()
    {
        return $this->belongsTo( Client::class, 'client_id' );
    }

    public function headquarter()
    {
        return $this->belongsTo( Headquarter::class, 'headquarter_id' );
    }

    public function visits()
    {
        return $this->belongsToMany(
            Visit::class,
            'quotations_has_visits',
            'quotation_id',
            'visit_id'
        );
    }
}

