<?php

namespace App\Livewire\TitlePhoto;

use App\Models\TitlePhoto;
use Livewire\Component;

class Show extends Component
{
    public TitlePhoto $titlePhoto;

    public function mount(TitlePhoto $titlePhoto): void
    {
        $this->titlePhoto = $titlePhoto;
    }

    public function render()
    {
        return view('livewire.titlePhoto.show');
    }
}
