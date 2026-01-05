<?php

namespace App\Livewire\TitlePhoto;

use App\Models\TitlePhoto;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    public TitlePhoto $titlePhoto;

    #[Locked]
    public $id;

    public $title = '';
    public $status = true;

    public function mount(TitlePhoto $titlePhoto)
    {
        $this->titlePhoto = $titlePhoto?->exists ? $titlePhoto : new TitlePhoto();

        $this->id = $this->titlePhoto->id;
        $this->title = $this->titlePhoto->title ?? '';
        $this->status = $this->titlePhoto->exists ? (bool) $this->titlePhoto->status : true;
    }

    public function render()
    {
        return view('livewire.titlePhoto.form');
    }

    protected function rules(): array
    {
        return [
            'title' => [
                'required',
                'min:3',
                'max:120',
                Rule::unique('title_photos', 'title')->ignore($this->id),
            ],
            'status' => ['boolean'],
        ];
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'title' => trim($this->title),
            'slug' => $this->generateUniqueSlug($this->title),
            'status' => (bool) $this->status,
        ];

        $this->titlePhoto->fill($payload);
        $this->titlePhoto->save();

        toastr()->success('El título se ha guardado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.title-photo.index');
    }

    protected function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug !== '' ? $baseSlug : Str::random(8);

        $originalSlug = $slug;
        $counter = 1;

        while (
            TitlePhoto::where('slug', $slug)
                ->when($this->id, fn ($query) => $query->where('id', '!=', $this->id))
                ->exists()
        ) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
