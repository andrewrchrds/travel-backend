<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itinerary>
 */
class ItineraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = fake()->city();
        $trip_start = fake()->dateTimeBetween('+1 month', '+1 year');
        $trip_end = fake()->dateTimeBetween($trip_start, $trip_start->format('Y-m-d').' +2 week');

        return [
            'title' => 'My trip to '.$city,
            'destination' => $city.', '.fake()->stateAbbr(),
            'trip_start' => $trip_start,
            'trip_end' => $trip_end,
        ];
    }
}
