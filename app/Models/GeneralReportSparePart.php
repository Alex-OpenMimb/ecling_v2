<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralReportSparePart extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'general_report_spare_parts';
    protected $fillable = [
        'amount',
        'spare_part_id',
        'general_report_id'
    ];
}
