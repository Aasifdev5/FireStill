<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\OpeningHour;

class RestaurantController extends Controller
{
    public function index()
    {
        if (!Session::has('LoggedIn')) {
            return redirect()->back()->with('fail', 'Tienes que iniciar sesión primero');
        }

        $user_session = User::find(Session::get('LoggedIn'));
        $restaurants = Restaurant::with('openingHours')->get();

        return view('admin.restaurants.index', compact('restaurants', 'user_session'));
    }

    public function store(Request $request)
    {
        if (!Session::has('LoggedIn')) {
            return redirect()->back()->with('fail', 'Tienes que iniciar sesión primero');
        }

        $restaurant = Restaurant::create($request->only(['name', 'address', 'latitude', 'longitude']));

        foreach ($request->days as $index => $day) {
            $open = $request->open_times[$index] ?? null;
            $close = $request->close_times[$index] ?? null;

            // Only create if either open or close is filled
            if ($open || $close) {
                OpeningHour::create([
                    'restaurant_id' => $restaurant->id,
                    'day' => $day,
                    'open_time' => $open,
                    'close_time' => $close,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Restaurante creado correctamente.');
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        if (!Session::has('LoggedIn')) {
            return redirect()->back()->with('fail', 'Tienes que iniciar sesión primero');
        }

        $restaurant->update($request->only(['name', 'address', 'latitude', 'longitude']));

        // Delete existing opening hours
        $restaurant->openingHours()->delete();

        // Add new ones
        foreach ($request->days as $index => $day) {
            $open = $request->open_times[$index] ?? null;
            $close = $request->close_times[$index] ?? null;

            if ($open || $close) {
                OpeningHour::create([
                    'restaurant_id' => $restaurant->id,
                    'day' => $day,
                    'open_time' => $open,
                    'close_time' => $close,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Restaurante actualizado correctamente.');
    }

    public function destroy(Restaurant $restaurant)
    {
        if (!Session::has('LoggedIn')) {
            return redirect()->back()->with('fail', 'Tienes que iniciar sesión primero');
        }

        // Delete related opening hours
        $restaurant->openingHours()->delete();
        $restaurant->delete();

        return redirect()->back()->with('success', 'Restaurante eliminado correctamente.');
    }
    // New endpoint to fetch opening hours for a restaurant
    public function getOpeningHours($restaurant_id)
    {
        try {
            $openingHours = OpeningHour::where('restaurant_id', $restaurant_id)->get();

            if ($openingHours->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron horarios para este restaurante',
                ], 404);
            }

            return response()->json($openingHours, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los horarios del restaurante',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getCoordinates($id)
{
    $restaurant = Restaurant::find($id);

    if (!$restaurant) {
        return response()->json([
            'error' => 'Restaurant not found',
            'message' => 'No restaurant found with the provided ID'
        ], 404);
    }

    if (!$restaurant->latitude || !$restaurant->longitude) {
        return response()->json([
            'error' => 'Coordinates not available',
            'message' => 'Restaurant coordinates are not set'
        ], 404);
    }

    return response()->json([
        'latitude' => (float) $restaurant->latitude,
        'longitude' => (float) $restaurant->longitude
    ]);
}
}
