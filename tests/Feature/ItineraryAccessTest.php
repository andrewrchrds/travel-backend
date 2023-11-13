<?php 

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Itinerary;

class ItineraryAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_only_see_their_own_itineraries()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create itineraries for each user
        $itinerary1 = Itinerary::factory()->create(['user_id' => $user1->id]);
        $itinerary2 = Itinerary::factory()->create(['user_id' => $user2->id]);

        // User 1 attempts to access both itineraries
        $response1 = $this->actingAs($user1)->getJson('/api/itineraries/'.$itinerary1->id);
        $response1->assertOk();

        $response2 = $this->actingAs($user1)->getJson('/api/itineraries/'.$itinerary2->id);
        $response2->assertForbidden(); // or assertStatus(403)

        // User 2 attempts to access both itineraries
        $response3 = $this->actingAs($user2)->getJson('/api/itineraries/'.$itinerary2->id);
        $response3->assertOk();

        $response4 = $this->actingAs($user2)->getJson('/api/itineraries/'.$itinerary1->id);
        $response4->assertForbidden(); // or assertStatus(403)
    }

    public function test_itineraries_created_by_a_user_are_not_visible_to_others()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User 1 creates an itinerary
        $this->actingAs($user1)->postJson('/api/itineraries', [
            'name' => 'User 1 Itinerary',
            'location' => 'Some Location',
            // Add other necessary fields
        ]);

        // User 2 tries to access the list of itineraries
        $response = $this->actingAs($user2)->getJson('/api/itineraries');

        // Assert that the response does not contain User 1's itinerary
        $response->assertOk();
        $response->assertJsonMissing(['name' => 'User 1 Itinerary']);
    }

}
