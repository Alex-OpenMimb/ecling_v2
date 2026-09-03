<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationVisit extends Model
{
    use HasFactory;

    protected $table = 'quotations_has_visits';

    protected $fillable = [
        'quotation_id',
        'visit_id',
    ];

    public function quotation()
    {
        return $this->belongsTo( Quotation::class, 'quotation_id' );
    }

    public function visit()
    {
        return $this->belongsTo( Visit::class, 'visit_id' );
    }
}

