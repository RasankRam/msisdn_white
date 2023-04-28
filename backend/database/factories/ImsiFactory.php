<?php

namespace Database\Factories;

use App\Helpers\AppHelper;
use App\Models\Imsi;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImsiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Imsi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'imsi' => AppHelper::code()
        ];
    }
}
