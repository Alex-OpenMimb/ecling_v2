<?php

namespace Database\Seeders;

use App\Models\CoreConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LimitedHoursSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       if ( !CoreConfig::count() ){
           CoreConfig::create([
               'code'=>'report_limited_hours',
               'value'=> 12,
           ]);
       }
    }
}
