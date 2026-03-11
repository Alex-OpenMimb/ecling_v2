<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitUser extends Model
{
    use HasFactory;

    protected $table = 'visits_users';
    protected $fillable = [
        'user_id',
        'visit_id',
    ];


}
