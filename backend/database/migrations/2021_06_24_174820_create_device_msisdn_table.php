<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceMsisdnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_msisdn', function (Blueprint $table) {
            $table->primary(['device_id','msisdn_id']);
            $table->foreignId('device_id')->constrained('devices');
            $table->foreignId('msisdn_id')->constrained('msisdns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_msisdn');
    }
}
