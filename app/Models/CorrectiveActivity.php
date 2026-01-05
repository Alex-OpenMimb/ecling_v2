<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrectiveActivity extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'corrective_activities';
    protected $fillable = [
        'activity',
        'description',
        'status',
        'assigned',
        'equipment_class_id',
    ];


    const ACTIVITIES = [
          [
              'activity'=>'Cambiar fogón',
              'equipment_class_id' => 1
          ],
        [
            'activity'=>'Cambiar tarjeta',
            'equipment_class_id' => 1
        ],
        [
            'activity'=>'Cambiar cables',
            'equipment_class_id' => 2
        ],
        [
            'activity'=>'Reparar tablero',
            'equipment_class_id' => 2
        ],
        [
            'activity'=>'Cambiar refrigerante',
            'equipment_class_id' => 3
        ],
        [
            'activity'=>'Reparar puerta',
            'equipment_class_id' => 3
        ],
    ];

    //Relationships

    public function equipmentClass()
    {
        return $this->belongsTo(EquipmentClass::class);
    }

    public function clientEquipments()
    {
        $this->belongsToMany( ClientsEquipments::class );
    }

    public function geeralReports()
    {
        return $this->belongsToMany( GeneralReport::class );
    }


   //Scopes

    public function scopeGetCorrectiveActivitiesByClass( Builder $query,$equipment_class_id )
    {
        $query->where('status',true)
            ->where('equipment_class_id', $equipment_class_id)
            ->select('id','activity');
    }


    ///Setters
    protected function activity(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucfirst($value) : $value;
            },
        );
    }
}
