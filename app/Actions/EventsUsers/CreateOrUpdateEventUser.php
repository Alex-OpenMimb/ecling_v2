<?php

namespace App\Actions\Visits;

use App\Models\Client;
use App\Models\EventUser;
use App\Models\Headquarter;
use App\Models\Visit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrUpdateEventUser
{
    use AsAction;

    public function handle(array $data)
    {
           [ 'user_id'=>$user_id, 'event_id'=>$event_id] = $data;
           EventUser::create(
               [
                   'event_id' =>$event_id,
                   'user_id'=> $user_id,
               ]
           );
    }
}
