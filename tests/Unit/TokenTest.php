<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class TokenTest extends TestCase
{
    public function test_token_generation_and_expiry()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $token = $user->createToken('TestToken')->accessToken;

        $this->assertNotNull($token);

        $decoded = (array) json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

        $this->assertEquals($user->id, $decoded['sub']);
        $this->assertTrue(isset($decoded['exp']));
    }
}
