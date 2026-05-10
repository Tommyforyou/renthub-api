<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Vehicle;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('vehicle.reviews')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Vehicle $vehicle)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'vehicle_id' => $vehicle->id,
        ]);

        return back()->with('success', 'Vehicle added to favorites.');
    }

    public function destroy(Vehicle $vehicle)
    {
        Favorite::where('user_id', auth()->id())
            ->where('vehicle_id', $vehicle->id)
            ->delete();

        return back()->with('success', 'Vehicle removed from favorites.');
    }
}