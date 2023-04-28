<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\Device;
use App\Models\Imsi;
use Illuminate\Database\Seeder;

class ImsisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \App\Models\Imsi::factory(55)->create();

      AppHelper::o_t_o_filled(Device::class,'device_id',Imsi::class,'imsi');
    }
}
