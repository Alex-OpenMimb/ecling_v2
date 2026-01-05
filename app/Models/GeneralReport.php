<?php

namespace App\Models;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralReport extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'general_reports';
    protected $fillable = [
        'serial',
        'date',
        'start_hour',
        'end_hour',
        'start_time',
        'end_time',
        'time_spent',
        'first_photo',
        'second_photo',
        'operator',
        'description_service',
        'observations',
        'pending_note',
        'receptor_name',
        'receptor_signature',
        'receptor_document',
        'receptor_document_type',
        'receptor_position',
        'preventive',
        'corrective',
        'stored',
        'pending',
        'sent',
        'status',
        'user_id',
        'client_id',
        'headquarter_id',
        'client_has_equipment_id',
        'equipment_class_id',
        'service_order_id',
        'preventive_routine',
        'stored_time',
        'request_name',
    ];

    //Relationships
    public function users()
    {
        return  $this->belongsTo(User::class);
    }

    public function serviceOrders()
    {
        return $this->belongsTo( ServiceOrder::class  );
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function headquarter()
    {
        return $this->belongsTo( Headquarter::class );
    }

    public function clientEquipments()
    {
        return $this->belongsTo( ClientsEquipments::class );
    }

    public function preventiveActivities()
    {
        return $this->belongsToMany( PreventiveActivity::class );
    }

    public function correctiveActivities()
    {
        return $this->belongsToMany( CorrectiveActivity::class );
    }

    public function materials()
    {
        return $this->belongsToMany( Material::class );
    }

    public function spareParts()
    {
        return $this->belongsToMany( SparePart::class );
    }

    public function equipmentClass()
    {
        return $this->belongsTo(EquipmentClass::class);
    }

    public function pendingActivity()
    {
        return $this->hasOne( PendingActivity::class );
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'model');
    }


    //Setters
    protected function descriptionService(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    protected function observations(): Attribute
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

    public function requestName(): Attribute
    {
        return Attribute::make(
            set: function ( $value){
                if (preg_match('/^\s*$/', $value) ) {
                    return null;
                } else {
                    return $value ;
                }

            }
        );
    }


}
