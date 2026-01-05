<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralReportMaterial extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'general_report_materials';
    protected $fillable = [
        'amount',
        'material_id',
        'general_report_id',
        'unit_id'
    ];



    //Relationships

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
