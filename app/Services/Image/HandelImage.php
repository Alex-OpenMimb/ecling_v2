<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;

class HandelImage
{
    public  static function delte_exsited_photo( $path  )
    {
        $extensions = ['jpeg','png','jpg'];
        foreach ($extensions as $index => $ext  ){
            if( Storage::disk('public')->exists($path.'.'.$ext) ) {
                Storage::disk('public')->delete($path.'.'.$ext);
                break;
            }
        }

    }
}
