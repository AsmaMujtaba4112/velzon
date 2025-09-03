<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LocationFactory extends Factory
{
    protected $model = \App\Models\Location::class;

    public function definition()
    {
        $name = $this->faker->city();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1,9999),
            'status' => $this->faker->randomElement(['Active','Inactive']),
        ];
    }
}
