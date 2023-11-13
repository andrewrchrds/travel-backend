<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'destination',
        'trip_start',
        'trip_end'
    ];
    
    protected $hidden = [
        'user_id'
    ];

    public function items()
    {
        return $this->hasMany(ItineraryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
