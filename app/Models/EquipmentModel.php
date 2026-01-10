<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentModel extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'equipment_models';
    protected $fillable = [
        'model',
        'status',
        'equipment_class_id',
    ];

    const MODEL = [
        [
            'model'=> 'SS-10',
            'equipment_class_id'=> 1,
        ],
        [
            'model'=> 'SS-20',
            'equipment_class_id'=> 1,
        ],
        [
            'model'=> 'TT-508',
            'equipment_class_id'=> 1,
        ],
        [
            'model'=> 'TT-518',
            'equipment_class_id'=> 1,
        ],
        [
            'model'=> 'TSR-889',
            'equipment_class_id'=> 1,
        ],
        [
            'model'=> 'TTRR-558',
            'equipment_class_id'=> 1,
        ],
    ];

    //Relationships

    public function equipments()
    {
        return $this->hasMany( Equipment::class );
    }

    public function equipmentClass()
    {
        return $this->belongsTo( EquipmentClass::class );
    }


    //Setter
    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value)
        );
    }
}
