<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralHelper
{

    public static function concatenate( $user_name )
    {
        $names = '';
        foreach ( $user_name as $index => $name ){
            $names .= $name['name']. ', ';
        }
        return rtrim($names,', ');
    }


    public static function set_auth_users( )
    {
        $user =  auth()->user();
        $user_id = $user->roles()->first()->id;

        $users =  DB::table('users')->select('id')->pluck('id')->toArray();

        if( $user_id === 1 || $user_id === 2 ||  $user_id === 3  ){
            $search_users = $users;
        }else{
            $search_users = [$user->id];
        }

        return $search_users;
    }


    public static function show_error_msm( $e )
    {
        $message = null;
        if (app()->environment('local')) {
            $message = $e->getMessage();
        }elseif ( app()->environment('production') ){
            $message = config('app.error_msm');
        }
        return $message;
    }

    public  static function getImageUrl($filePath)
    {
        return asset('storage/' . $filePath);
    }
}
