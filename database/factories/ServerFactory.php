<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->word(),
            'ip'                => fake()->domainName(),
            'type'              => 'vanilla',
            'current_version'   => '1.20.2',
            'api_key'           => fake()->uuid(),
        ];
    }
}
