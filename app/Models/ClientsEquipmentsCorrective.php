<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientsEquipmentsCorrective extends Model
{
    use HasFactory;

    protected $table = 'clients_equipments_correctives';
    protected $fillable = [
        'client_has_equipment_id',
        'corrective_activity_id',
        'equipment_class_id',
        'corrective_service_id',

    ];


    //Relationships

    public function equipmentClass()
    {
        $this->belongsTo( EquipmentClass::class );
    }

    public function correctiveService()
    {
        return $this->belongsTo(CorrectiveService::class);
    }

    //Getters and setters

    public function observations(): Attribute
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
