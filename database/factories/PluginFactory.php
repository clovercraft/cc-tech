<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plugin>
 */
class PluginFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => fake()->word(),
            'description'   => fake()->sentence(),
            'source'        => fake()->url(),
            'current'       => fake()->semver(),
            'latest'        => fake()->semver(),
            'in_use'        => true
        ];
    }
}
