<?php

namespace Database\Factories\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CMS\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug'  => fake()->slug(),
            'title' => fake()->words(2, true),
            'user'  => User::factory()->create(),
        ];
    }
}
