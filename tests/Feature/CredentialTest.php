<?php

namespace Tests\Feature;

use App\Models\Credential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CredentialTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_factory(): void
    {
        $cred = Credential::factory()->create();
        $this->assertModelExists($cred);
    }

    public function test_values_are_encrypted(): void
    {
        $cred = Credential::factory()->create();
        $record = DB::table('credentials')
            ->select()
            ->where('id', '=', $cred->id)
            ->first();

        $this->assertEquals($cred->name, $record->name);

        $encrypted = [
            'url',
            'username',
            'password',
            'notes'
        ];
        foreach ($encrypted as $property) {
            $this->assertNotEquals($cred->$property, $record->$property);
        }
    }
}
