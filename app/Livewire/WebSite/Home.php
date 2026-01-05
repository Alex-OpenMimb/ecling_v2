<?php

namespace App\Livewire\WebSite;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Home extends Component
{

    #[Layout('layouts.webSite')]
    public function render()
    {
        return view( 'livewire.webSite.home');
    }


}
