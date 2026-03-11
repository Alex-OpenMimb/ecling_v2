<?php

namespace App\Livewire\Visit;

use App\Helper\GeneralHelper;
use App\Models\Event;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveData;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Event\EventServices;
use App\Services\Schedule\ScheduleData;
use App\Services\Schedule\ServicesSchedule;
use App\Services\ServiceOrder\ServiceOrderHandle;
use App\Services\User\DataUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Closure;

class FormVisit  extends Component
{


    public function mount()
    {



    }


    public function render()
    {
        return view('livewire.visit.form');
    }







}
