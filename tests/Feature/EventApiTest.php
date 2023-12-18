<?php

namespace Tests\Feature;

use App\Facades\Minecraft;
use App\Models\MinecraftAccount;
use App\Models\Server;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventApiTest extends TestCase
{

    use RefreshDatabase;

    public $player;
    public $server;

    protected function setUp(): void
    {
        parent::setUp();
        $this->player = MinecraftAccount::factory()->create();
        $this->server = Server::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_logon_event(): void
    {
        $sampleRequest = [
            'event_type'    => 'logon',
            'player_id'     => $this->player->uuid,
            'api_token'     => $this->server->api_key,
        ];

        $response = $this->post('/mcevents/log', $sampleRequest);

        $response->assertStatus(200);

        $this->assertDatabaseHas('minecraft_events', [
            'event_type'            => 'logon',
            'minecraft_account_id'  => $this->player->id
        ]);
    }

    public function test_shutdown_event(): void
    {
        $response = $this->post('/mcevents/global', [
            'event_type'    => 'shutdown',
            'api_token'     => $this->server->api_key
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('minecraft_events', [
            'event_type'            => 'shutdown',
        ]);
    }
}
