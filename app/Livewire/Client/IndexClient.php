<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;

class IndexClient extends Component
{
    public $records;

    public function mount()
    {
        $this->records = Client::all()->count();
    }
    public function render()
    {
        return view('livewire.client.index');

    }
}
