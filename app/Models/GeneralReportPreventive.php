<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralReportPreventive extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'general_report_preventive';
    protected $fillable = [
        'preventive_activity_id',
        'general_report_id'
    ];
}
