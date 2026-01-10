<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreventiveActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'preventive_activities';
    protected $fillable = [
        'activity',
        'description',
        'status',
        'equipment_class_id',
    ];


    const ACTIVITIES = [

        [ 'activity'=>'Cambiar gas',
          'equipment_class_id' => 1
        ],
        [ 'activity'=>'Limpiar fogón',
            'equipment_class_id' => 1
        ],
        [ 'activity'=>'Cambiar cables',
            'equipment_class_id' => 1
        ],
        [ 'activity'=>'Cambiar capacitor',
            'equipment_class_id' => 1
        ],
        [ 'activity'=>'Cambiar refreigerante',
            'equipment_class_id' => 1
        ],
        [ 'activity'=>'Retirar exceso de hielo',
            'equipment_class_id' => 1
        ],
    ];


    //Relationships

    public function equipmentClass()
    {
        return $this->belongsTo(EquipmentClass::class);
    }


    public function preventiveRoutines()
    {
        return $this->belongsToMany( PreventiveRoutine::class );
    }

    public function geeralReports()
    {
        return $this->belongsToMany( GeneralReport::class );
    }





    //Setters
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

    //Scope

    public function scopeGetActivities(Builder $query )
    {
        $query->select('activity','id');
    }

    public function scopeGetActivitiesByClass(Builder $query,$class_id)
    {
        $query->where('equipment_class_id',$class_id)
            ->where('status',true)
            ->select('id','activity');
    }

    /**
     * Verifica si la actividad preventiva está asociada a un reporte general
     * 
     * @return bool
     */
    public function isAssociatedWithGeneralReport(): bool
    {
        return GeneralReportPreventive::where('preventive_activity_id', $this->id)
            ->whereNull('deleted_at')
            ->exists();
    }

}
