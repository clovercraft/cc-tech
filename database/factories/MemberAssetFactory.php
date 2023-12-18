<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberAsset>
 */
class MemberAssetFactory extends Factory
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
            'uuid'          => fake()->uuid(),
            'visibility'    => 'public',
            'member_id'     => Member::factory()->create()
        ];
    }
}
