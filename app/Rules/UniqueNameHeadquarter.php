<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueNameHeadquarter implements ValidationRule
{




    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existsInHeadquarters = DB::table('headquarters')
            ->where('name', $value)
            ->where('id', '!=', $this->ignoreId)
            ->exists();
        if( $existsInHeadquarters ) $fail('El nombre de la se sede ya existe.');
    }

}
