<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\Device;
use App\Models\Msisdn;
use Illuminate\Database\Seeder;

class DevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Device::factory(35)->create();
    }
}
