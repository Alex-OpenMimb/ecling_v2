<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitReason extends Model
{
    use HasFactory;

    protected $table = 'visit_reasons';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function visits()
    {
        return $this->hasMany( Visit::class, 'visit_reason_id' );
    }
}

