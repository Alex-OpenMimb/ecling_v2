<?php

namespace App\Services\User;

use App\Models\User;

class DataUser
{


    public static function get_users_by_id( $user_list )
    {
        return User::with(['roles'=>function($query){
            $query->select('name as role_name');

        }])->whereIn('users.id',$user_list)
            ->select('id','name')
            ->notAdmin()
            ->get()->toArray();
    }


    public static function get_users()
    {
        return User::with(['roles'=>function($query){
            $query->select('name as role_name');

        }])->select('id','name')
            ->notAdmin()
            ->get()->toArray();
    }
}
