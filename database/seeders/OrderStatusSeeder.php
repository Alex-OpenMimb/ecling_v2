<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Abierta',
            'Cerrada',
            'Rechazada',
            'Declinada',
            'Facturada',
            'Remision',
        ];

        foreach ($statuses as $name) {
            OrderStatus::firstOrCreate(
                ['code' => Str::slug($name, '-')],
                [
                    'name' => $name,
                    'description' => null,
                    'state' => true,
                ]
            );
        }
    }
}
