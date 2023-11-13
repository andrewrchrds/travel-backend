<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ItineraryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_itinerary_and_add_items()
    {
        // Create a user
        $user = User::factory()->create();

        // User creates an itinerary
        $itineraryResponse = $this->actingAs($user)->postJson('/api/itineraries', [
            'title' => 'My Itinerary',
            'destination' => 'Some Location',
            'trip_start' => '2025-01-01',
            'trip_end' => '2025-01-10',
        ]);

        $itineraryResponse->assertCreated();
        $itineraryId = $itineraryResponse->json('id');

        // User adds items to the itinerary
        $this->actingAs($user)->postJson("/api/itineraries/{$itineraryId}/items", [
            'description' => 'Item 1'
        ])->assertCreated();

        $this->actingAs($user)->postJson("/api/itineraries/{$itineraryId}/items", [
            'description' => 'Item 2'
        ])->assertCreated();

        // Retrieve the itinerary with items
        $response = $this->actingAs($user)->getJson("/api/itineraries/{$itineraryId}");

        $response->assertOk();
        $response->assertJsonPath('title', 'My Itinerary');
        $response->assertJsonCount(2, 'items'); // Ensure there are 2 items
    }
}
