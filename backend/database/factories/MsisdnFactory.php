<?php

namespace Database\Factories;

use App\Helpers\AppHelper;
use App\Models\Device;
use App\Models\Msisdn;
use Illuminate\Database\Eloquent\Factories\Factory;

class MsisdnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Msisdn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'msisdn' => AppHelper::code(11,7),
        ];
    }
}

