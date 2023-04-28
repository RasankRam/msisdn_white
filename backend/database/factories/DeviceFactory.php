<?php

namespace Database\Factories;

use App\Helpers\AppHelper;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(11),
            'imei' => AppHelper::code(),
            'user_id' => rand(0,1) ? User::inRandomOrder()->first() : null,
        ];
    }
}
