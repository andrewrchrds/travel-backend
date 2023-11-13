<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\EnrichItinerary;
use App\Models\Itinerary;

class ItineraryController extends Controller
{
    /**
     * List all itineraries for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json($user->itineraries);
    }

    /**
     * Create a new itinerary for the authenticated user
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255',
            'trip_start' => 'required|date',
            'trip_end' => 'required|date|after_or_equal:trip_start',
            'destination' => 'required|string',
        ]);

        $itinerary = $user->itineraries()->create([
            'title' => $request->title,
            'trip_start' => $request->trip_start,
            'trip_end' => $request->trip_end,
            'destination' => $request->destination,
        ]);
        // add an enrichment job to the queue
        EnrichItinerary::dispatch($itinerary);

        return response()->json($itinerary, 201);
    }

    /**
     * Show an itinerary
     */
    public function show(Itinerary $itinerary)
    {
        // Check if the user is authorized to view the itinerary
        // This uses a policy
        $this->authorize('view', $itinerary);
        // Load the itinerary with its items
        $itinerary->load('items');
        return response()->json($itinerary);
    }

    /**
     * Update an itinerary
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $request->validate([
            'title' => 'string|max:255',
            'trip_start' => 'date',
            'trip_end' => 'date|after_or_equal:trip_start',
            'destination' => 'string',
        ]);

        $itinerary->update($request->only(['title', 'trip_start', 'trip_end', 'destination']));

        return response()->json($itinerary);
    }

    /**
     * Delete an itinerary
     */
    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);
        $itinerary->delete();
        return response()->json(null, 204);
    }
}
