<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\Device;
use App\Models\Msisdn;
use Illuminate\Database\Seeder;

class MsisdnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Msisdn::factory(55)->create();
      AppHelper::o_t_o_filled(Device::class,'device_id',Msisdn::class,'msisdn');
    }
}
