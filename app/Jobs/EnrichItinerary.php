<?php

namespace App\Jobs;

use App\Events\ItineraryEnriched;
use App\Models\Itinerary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Helper method to extract the first sentence from a string
 */
function getFirstSentence($text) {
    // Use regular expression to match the first sentence
    // This pattern looks for any text ending with '.', '!', or '?' followed by a space or end of the text
    $pattern = '/^.*?[.!?](\s|$)/';

    // Perform the regex matching
    if (preg_match($pattern, $text, $matches)) {
        return $matches[0];
    }

    // Return the original text if no sentence-ending punctuation is found
    return $text;
}


class EnrichItinerary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $itinerary;

    public function __construct(Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;
    }

    public function handle()
    {
        // Make an HTTP request to the Wikipedia API
        try {
            $destination = urlencode(str_replace(' ', '_', $this->itinerary->destination));
            $url = "https://en.wikipedia.org/api/rest_v1/page/summary/$destination";
            Log::info("Enriching itinerary ".$this->itinerary->id." from Wikipedia at URL ".$url);
            
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                $this->itinerary->enriched_image = $data['thumbnail']['source'];
                $this->itinerary->enriched_info = getFirstSentence($data['extract']);
                $this->itinerary->save();
                // broadcast it to the UI
                broadcast(new ItineraryEnriched($this->itinerary));
                Log::info("Finished enriching ".$this->itinerary->id." from Wikipedia");
            } else {
                Log::error("Failed to enrich itinerary ".$this->itinerary->id.": " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Failed to enrich itinerary: " . $e->getMessage());
        }


    }
}
