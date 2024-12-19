<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;


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
    public function process(Request $request)
    {
        $request->validate([
            'payment-method' => 'required|in:Card,Paypal,Mbway',
            'card-number' => 'required_if:payment-method,Card|max:16',
            'expiry-date' => 'required_if:payment-method,Card|date_format:Y-m',
            'cvv' => 'required_if:payment-method,Card|digits:3',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $items = ShoppingCart::with('_product')
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('checkout.show', ['id' => $user->id])
                ->with('error', 'Seu carrinho está vazio! Adicione itens antes de prosseguir.');
        }

        $total = $items->sum(function ($item) {
            return $item->quantity * $item->_product->price;
        });

        $paymentMethodsMap = [
            'Card' => 1,
            'Paypal' => 2,
            'Mbway' => 3,
        ];

        $payment = new Payment();
        $payment->type = $paymentMethodsMap[$request->input('payment-method')];
        dd($payment->type);
        $payment->save();

        $order = new Order();
        $order->buyer = $user->id;
        $order->status = 'New';
        $order->shipping_address = $request->input('shipping_address');
        $order->payment_id = $payment->id;
        $order->save();

        foreach ($items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product = $item->_product->id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->_product->price;
            $orderItem->save();

            $product = $item->_product;
            $product->stock -= $item->quantity;
            $product->save();
        }

        ShoppingCart::where('user_id', $user->id)->delete();

        // Notificação (descomente ao implementar o modelo)
        // Notification::create([
        //     'type' => 'OrderStatusChangeNotification',
        //     'user_id' => $user->id,
        //     'order_id' => $order->id,
        //     'data' => json_encode(['status' => $order->status]),
        // ]);

        return view('pages.order-confirmation', [
            'order' => $order,
        ]);
    }
}
