<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'slug',
        'email',
        'document',
        'session_id',
        'last_session',
        'phone',
        'status',
        'url_image',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    //Relationships
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function visits()
    {
        return $this->belongsToMany(Visit::class, 'visits_users', 'user_id', 'visit_id');
    }

    public function serviceOrders()
    {
        return $this->belongsToMany( ServiceOrder::class );
    }


    //It is a relationship to set the created user of service order
    public function serviceOrdersCreator()
    {
        return $this->hasMany(ServiceOrder::class,'user_id');
    }

    public function serviceOrdersRejecte()
    {
        return $this->hasMany(ServiceOrder::class, 'rejected_by');
    }

    public function EventCreator()
    {
        return $this->hasMany( Event::class,'user_id' );
    }

    public function generalReports()
    {
        return $this->hasMany( GeneralReport::class );
    }

    //Mutator and accessor
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                return $value ? ucwords($value) : $value;
            },
        );
    }



    //Get object by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //Scope

    public function scopeNotAdmin(Builder $query ):void
    {
        $query->whereNotIn('name',['Administrador']);
    }
}
