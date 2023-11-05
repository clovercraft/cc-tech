<?php

namespace Database\Factories;

use App\Models\DiscordMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class MinecraftAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discord_member_id' => DiscordMember::factory()->create(),
            'name'              => fake()->userName(),
            'uuid'              => fake()->uuid(),
            'status'            => 'active'
        ];
    }
}
