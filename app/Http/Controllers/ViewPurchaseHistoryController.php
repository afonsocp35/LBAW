<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ViewPurchaseHistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::where('buyer', $userId)
            ->with(['items._product'])
            ->orderBy('ordered_on', 'desc')
            ->get();

        return view('pages.purchase-history', ['orders' => $orders]);
    }

    public function show($id)
    {
        $orders = Order::where('buyer', $id)
            ->with(['items._product'])
            ->orderBy('ordered_on', 'desc')
            ->get();

        return view('pages.purchase-history', ['orders' => $orders]);
    }
}
