<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = Favorite::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->product->id,
                    'category' => $favorite->product->category,
                    'imageUrl' => $favorite->product->image,
                    'name' => $favorite->product->name,
                    'description' => $favorite->product->description,
                    'price' => $favorite->product->price ? '$' . number_format($favorite->product->price, 2) : 'Precio no disponible',
                ];
            });

        return response()->json(['favorites' => $favorites], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->input('product_id');

        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Producto ya está en favoritos'], 400);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        $favorites = Favorite::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->product->id,
                    'category' => $favorite->product->category,
                    'imageUrl' => $favorite->product->image,
                    'name' => $favorite->product->name,
                    'description' => $favorite->product->description,
                    'price' => $favorite->product->price ? '$' . number_format($favorite->product->price, 2) : 'Precio no disponible',
                ];
            });

        return response()->json(['favorites' => $favorites], 200);
    }

    public function remove($productId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Producto no encontrado en favoritos'], 404);
        }

        $favorite->delete();

        $favorites = Favorite::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->product->id,
                    'category' => $favorite->product->category,
                    'imageUrl' => $favorite->product->image,
                    'name' => $favorite->product->name,
                    'description' => $favorite->product->description,
                    'price' => $favorite->product->price ? '$' . number_format($favorite->product->price, 2) : 'Precio no disponible',
                ];
            });

        return response()->json(['favorites' => $favorites], 200);
    }
}
