<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{

    /**
     * Go to the checkout page.
     */
    public function show()
    {

        // not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $items = ShoppingCart::with('_product')
            ->where('user_id', Auth::id())
            ->get();

        // empty cart
        if ($items->isEmpty()) {
            //return redirect()->route('shopping-cart.show', ['id' => Auth::id()]);
            //    ->with('error', 'Your cart is empty, add items to it to check out!');
        }

        $total = $items->sum(function ($i) {
            return $i->quantity * $i->_product->price;
        });

        return view('pages.checkout', [
            'items' => $items,
            'total' => $total,
            'user' => Auth::user()
        ]);
    }

    /**
     * Process a purchase.
     */
    public function process(Request $request) //: View
    {
        return "To be implemented in A9 :)";
    }
}

