<?php

namespace App\Livewire\LimitedTimeReport;


use App\Models\CoreConfig;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;


class FormLimitedTimeReport extends ModalComponent
{

    public $limitedHours;

    public function mount()
    {
          $this->limitedHours =  CoreConfig::where( 'code','report_limited_hours' )->select('value')
              ->first();
          $this->limitedHours = $this->limitedHours ? $this->limitedHours->value : null;
    }


    public function render()
    {
        return view('livewire.limitedTimeReport.form');
    }


    public function updateOrStore()
    {
        $this->validate();
        CoreConfig::where('code','report_limited_hours')->update([
            'value' => $this->limitedHours
        ]);
        toastr()->success('Tiempo actualizado con éxito!', 'Felicitaciones');
        $this->closeModal();
    }


    protected function rules()
    {
        return [
            'limitedHours'=> [
                'required',
                'numeric',
                'min:1'
            ]
        ];
    }


    protected function messages()
    {
        return [
            'limitedHours.required' => 'El tiempo es requerido.',
            'limitedHours.numeric' => 'El valor debe ser un número.',
            'limitedHours.min' => 'El valor deber ser mayor a cero.',

        ];
    }


}
