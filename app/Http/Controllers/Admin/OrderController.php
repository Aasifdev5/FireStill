<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('LoggedIn')) {
            return redirect()->back()->with('fail', 'Tienes que iniciar sesión primero');
        }

        $user_session = User::find(Session::get('LoggedIn'));

        $query = Order::with('user');

        // Apply filters only if explicitly provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->orWhere('delivery_address', 'LIKE', "%$search%");
        }

        $orders = $query->latest()->get();

        // Log the number of orders fetched
        Log::debug('Fetched Orders Count: ' . $orders->count());

        // Process items to include product images
        foreach ($orders as &$order) {
            $items = $order->items ?? [];
            foreach ($items as &$item) {
                // Fetch image from Product model using product_id
                if (isset($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    $item['image'] = $product && $product->image ? asset($product->image) : asset('images/placeholder.png');
                    $item['name'] = $product ? ($product->name ?? $item['name']) : $item['name'];
                    // Log image URL for debugging
                    Log::debug('Item Image URL: ' . $item['image']);
                } else {
                    $item['image'] = asset('images/placeholder.png');
                }
            }
            $order->items = $items;
        }

        return view('admin.orders.index', compact('orders', 'user_session'));
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    public function markAsCompleted($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Order marked as completed']);
    }
}
