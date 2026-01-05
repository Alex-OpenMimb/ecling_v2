<?php

namespace App\Helper;

class HandleStatus
{
    public static function handle_status( $model,$entity )
    {
        $model->status = !$model->status;
        $model->save();
        $message = $model->status ? 'se activó' : ' se ha  desactivado';
        toastr()->success($entity.' '. $message .' con éxito!','Felicitaciones');

    }
}
