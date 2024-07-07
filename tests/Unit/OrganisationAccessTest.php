<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganisationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_organisation_data()
    {
        $organisation1 = Organisation::factory()->create();
        $organisation2 = Organisation::factory()->create();

        $user1 = User::factory()->create(['organisation_id' => $organisation1->id]);
        $user2 = User::factory()->create(['organisation_id' => $organisation2->id]);

        $this->actingAs($user1);

        $response = $this->get('/api/organisations/' . $organisation2->id);
        $response->assertStatus(403);
    }
}
