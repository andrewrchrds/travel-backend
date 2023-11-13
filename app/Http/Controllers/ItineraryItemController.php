<?php 

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Http\Request;

class ItineraryItemController extends Controller
{
    /**
     * Create a new Itinerary Item for a given Itinerary.
     */
    public function store(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $itineraryItem = $itinerary->items()->create($validatedData);

        return response()->json($itineraryItem, 201);
    }

    /**
     * Display a list of the Itinerary Items for a given Itinerary.
     */
    public function index(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);
        return response()->json($itinerary->items);
    }

    /**
     * Update a given Itinerary Item.
     */
    public function update(Request $request, Itinerary $itinerary, ItineraryItem $item)
    {
        $this->authorize('update', $itinerary);

        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $item->update($validatedData);

        return response()->json($item, 200);
    }

    /**
     * Delete a given Itinerary Item.
     */
    public function destroy(Itinerary $itinerary, ItineraryItem $item)
    {
        $this->authorize('delete', $itinerary);

        $item->delete();

        return response()->json(null, 204);
    }
}
