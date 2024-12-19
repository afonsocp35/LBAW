<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\Product;
use App\Models\ProductTemplate;
use App\Models\ProductPlatform;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{

    public function show()
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $id = Auth::id();

        $cartItems = ShoppingCart::with(['_product._template', '_product.platforms'])
        ->where('user_id', $id)
        ->get()
        ->map(function ($cartItem) {
            $product = Product::find($cartItem->product);
            $template = ProductTemplate::find($product->template);
            $platforms = ProductPlatform::where('product', $cartItem->product)->pluck('platform_name')->toArray();
    
            $price = $product->price;
    
            return [
                'product_id' => $cartItem->product,
                'productName' => $template?->name ?? 'Unknown',
                'image' => $template && $template->images()->exists() 
                    ? $template->images->first()->path 
                    : null, // First image or null
                'quantity' => $cartItem->quantity,
                'addedOn' => $cartItem->added_on,
                'price' => $price,
                'subtotal' => $cartItem->quantity * $price,
                'platforms' => $platforms, // Add platform data
                'stock' =>  $product->stock,
            ];
        });
    

        $totalCost = $cartItems->sum('subtotal');
    
        return view('pages.shopping-cart', [
            'cartItems' => $cartItems,
            'totalCost' => $totalCost,
        ]);
    }

    public function add(string $product_id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $id = Auth::id();
    
        $quantity = $request->input('quantity', 1);
    
        if ($quantity <= 0) {
            return response()->json(['message' => 'Invalid quantity'], 400);
        }
    
        // Retrieve the product and its stock
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $cartItem = ShoppingCart::where('user_id', $id)
                                ->where('product', $product_id)
                                ->first();
    
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;
    
        // Check stock availability
        if ($currentQuantity + $quantity > $product->stock) {
            return response()->json([
                'message' => 'Cannot add more items than available in stock.',
                'redirect' => url("/shopping-cart")
            ], 400);
        }
    
        if ($cartItem) {
            if ($currentQuantity + $quantity > 10) {
                return response()->json([
                    'message' => 'Cannot add more than 10 of the same product to your cart.',
                    'redirect' => url("/shopping-cart")
                ], 400);
            }
            $cartItem->quantity += $quantity;
            $cartItem->save();
            $message = 'Product quantity updated in your cart.';
        } else {
            if ($quantity > 10) {
                return response()->json([
                    'message' => 'Cannot add more than 10 of the same product to your cart.',
                    'redirect' => url("/shopping-cart")
                ], 400);
            }
            ShoppingCart::create([
                'user_id' => $id,
                'product' => $product_id,
                'quantity' => $quantity,
                'added_on' => now(),
            ]);
            $message = 'Product added to your cart.';
        }
    
        return response()->json(['message' => $message, 'redirect' => url("/shopping-cart")]);
    }
    

    public function update(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $id = Auth::id();

        $validated = $request->validate([
            'product_id' => 'required|exists:"Product",id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = ShoppingCart::where('user_id', $id)
            ->where('product', $validated['product_id'])
            ->first();
    
        if (!$cartItem) {
            return redirect()->route('shopping-cart.show')
                ->with('error', 'Item not found in the cart.');
        }

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();
    
        return redirect()->route('shopping-cart.show')
            ->with('status', 'Cart updated successfully!');
    }
    
    
    
    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $id = Auth::id();

        $validated = $request->validate([
            'product_id' => 'required|exists:Product,id',
        ]);

        $cartItem = ShoppingCart::where('user_id', $id)
            ->where('product', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->deleteCartItem($id, $validated['product_id']);
        } else {
            return redirect()->route('shopping-cart.show')
                ->with('error', 'Item not found in the cart.');
        }

        return redirect()->route('shopping-cart.show')
            ->with('status', 'Item removed successfully!');
    }
    
}
