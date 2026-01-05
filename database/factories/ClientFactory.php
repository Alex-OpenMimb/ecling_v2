<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company .rand(10, 1000);;
        return [
            'name'=>$name,
            'slug'=>Str::slug($name,'-'),
            'nit'=>  $this->nit_rand(),
        ];
    }



    function nit_rand() {
        $number = mt_rand(0, 9999999999);
        return str_pad($number, 10, '0', STR_PAD_LEFT);
    }
}
