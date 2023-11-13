<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

    protected $hidden = [
        "itinerary_id",
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
