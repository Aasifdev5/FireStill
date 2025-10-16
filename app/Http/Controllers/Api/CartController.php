<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Ensure user is authenticated
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart' => $this->getCartItems($user->id),
        ], 200);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }

        if ($cartItem->quantity > 1 && !$request->has('remove_all')) {
            // Decrease quantity
            $cartItem->quantity -= 1;
            $cartItem->save();
        } else {
            // Remove item
            $cartItem->delete();
        }

        return response()->json([
            'message' => 'Product updated/removed from cart',
            'cart' => $this->getCartItems($user->id),
        ], 200);
    }

    public function getCart()
    {
        $user = Auth::user();
        return response()->json([
            'cart' => $this->getCartItems($user->id),
        ], 200);
    }

    private function getCartItems($userId)
    {
        return Cart::where('user_id', $userId)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'description', 'price', 'image', 'category_id');
            }])
            ->get()
            ->map(function ($cartItem) {
                return [
                    'id' => $cartItem->product->id,
                    'name' => $cartItem->product->name,
                    'description' => $cartItem->product->description,
                    'price' => '$' . number_format($cartItem->product->price, 2),
                    'imageUrl' => $cartItem->product->image,
                    'category' => $cartItem->product->category_id,
                    'quantity' => $cartItem->quantity,
                ];
            });
    }

public function checkout(Request $request)
{
    // Define dynamic payment modes
    $allowedPaymentModes = ['cash', 'upi', 'tarjeta'];

    // Validate request
    $validator = Validator::make($request->all(), [
        'payment_mode' => 'required|in:' . implode(',', $allowedPaymentModes),
        'order_type' => 'required|in:delivery,takeaway,dine_in',
        'delivery_address' => 'required_if:order_type,delivery|string|nullable',
        'notes' => 'string|nullable',
        'delivery_cost' => 'required_if:order_type,delivery|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = Auth::user();
    $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }

    // Validate all cart items belong to the same restaurant
    $restaurantIds = $cartItems->pluck('product.created_by_restaurant')->unique();
    if ($restaurantIds->count() > 1) {
        return response()->json([
            'message' => 'All items must belong to the same restaurant',
        ], 400);
    }

    // Calculate subtotal
    $subtotal = $cartItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    // Include delivery cost for delivery orders
    $deliveryCost = $request->order_type === 'delivery' ? floatval($request->delivery_cost) : 0.0;
    $total = $subtotal + $deliveryCost;

    // Prepare order items
    $items = $cartItems->map(function ($item) {
        return [
            'product_id' => $item->product->id,
            'name' => $item->product->name,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ];
    })->toArray();

    try {
        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'restaurant_id' => $restaurantIds->first(),
            'subtotal' => $subtotal,
            'delivery_cost' => $deliveryCost,
            'total' => $total,
            'status' => 'pending',
            'payment_mode' => $request->payment_mode,
            'payment_status' => 'pending',
            'items' => $items,
            'delivery_address' => $request->order_type === 'delivery' ? $request->delivery_address : null,
            'order_type' => $request->order_type,
            'notes' => $request->notes,
        ]);

        // Create payment
        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'amount' => $total,
            'payment_receipt' => null,
            'accepted' => 0,
            'status' => 'initial',
        ]);

        // Clear cart
        Cart::where('user_id', $user->id)->delete();

        // Send SMS via Termii
        try {
            $restaurant = Restaurant::find($restaurantIds->first());
            $restaurantLocationLink = $restaurant ? "https://maps.google.com/?q={$restaurant->latitude},{$restaurant->longitude}" : 'N/A';
            $deliveryLocationLink = $request->order_type === 'delivery' ? "https://maps.google.com/?q={$request->delivery_address}" : 'N/A';
            $dateTime = now()->format('d/m/Y H:i');

            $smsMessage = "📦 Nuevo Pedido Asignado\n\n"
                . "Cliente: {$user->name}\n"
                . "Email: {$user->email}\n"
                . "Tel: {$user->phone}\n"
                . "Pedido: " . collect($items)->pluck('name')->implode(', ') . "\n"
                . "💵 Total: {$total}\n"
                . "🚚 Delivery: {$deliveryCost}\n"
                . "📍 Restaurante: {$restaurantLocationLink}\n"
                . "📬 Entrega: {$deliveryLocationLink}\n"
                . "🕒 Fecha y Hora: {$dateTime}\n"
                . "👉 Listo para recoger. Recuerda confirmar al llegar. ¡Buen viaje! 🛵";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://api.ng.termii.com/api/sms/send', [
                'to' => '59162095357', // Hardcoded recipient phone number
                'from' => 'Remoto',       // Replace with your Termii Sender ID
                'sms' => $smsMessage,
                'type' => 'plain',
                'channel' => 'generic',
                'api_key' => 'TLWztTzmEGubtdVeUeomacSVvmaDwVIkOVYDUzswYgZVfvcUIYWqEWpyYHMZpP', // Replace with your actual Termii API key
            ]);

            if (!$response->successful()) {
                \Log::error('Failed to send SMS via Termii: ' . $response->body());
            }
        } catch (\Exception $ex) {
            \Log::error('SMS sending error: ' . $ex->getMessage());
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_id' => $payment->id,
            'total' => $total,
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Checkout error: ' . $e->getMessage());
        return response()->json([
            'message' => 'Error processing order',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
