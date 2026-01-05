<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pending_activities';
    protected $fillable = [
        'pending_note',
        'management_observations',
        'status',
        'client_id_flag',
        'headquarter_id_flag',
        'client_has_equipment_id_flag',
        'equipment_class_id_flag',
        'service_order_id_flag',
        'preventive',
        'corrective',
        'general_report_id',
    ];

    //Relationships
    public function GeneralReport()
    {
        return $this->belongsTo( GeneralReport::class );
    }

    //Mutator and accessor
    protected function managementObservations(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    protected function pendingNote(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }



}
