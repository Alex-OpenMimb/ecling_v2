<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationStatus extends Model
{
    use HasFactory;

    protected $table = 'quotation_status';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function quotations()
    {
        return $this->hasMany( Quotation::class, 'quotation_status_id' );
    }
}

