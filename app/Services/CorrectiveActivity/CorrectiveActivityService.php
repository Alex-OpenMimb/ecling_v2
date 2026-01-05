<?php

namespace App\Services\CorrectiveActivity;

use App\Models\CorrectiveActivity;

class CorrectiveActivityService
{

    public static function assign( $id )
    {
        CorrectiveActivity::where('id',$id)->update([
            'assigned' => 1
        ]);
    }
}
