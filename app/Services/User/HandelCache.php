<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Cache;

class HandelCache
{
    public static function deleted_cache( $type )
    {
        $key = self::key_cache( $type);
        Cache::forget( $key );
    }

    public static function key_cache( $type )
    {
        $user_id   = auth()->user()->id;
        return  $type.'-'. $user_id;
    }


    public static function put_cahce( $lists, $type )
    {
        $key =  self::key_cache( $type );
        Cache::put($key,$lists);
    }


    public static function put_event_cache( $type, $event_id )
    {
        $user_id   = auth()->user()->id;
        $key = 'event-'.$type.'-'.$user_id;
        Cache::put( $key,$event_id );

    }

    public static function put_users_cache( $users )
    {
        $user_id   = auth()->user()->id;
        $key = 'users-planner-'.$user_id;
        Cache::put( $key,$users );
    }
}
