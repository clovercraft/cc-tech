<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Credential>
 */
class CredentialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => fake()->word(),
            'url'       => fake()->url(),
            'username'  => fake()->userName(),
            'password'  => fake()->password(),
            'notes'     => fake()->sentence(),
        ];
    }
}
