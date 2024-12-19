<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Notification;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $chartData = $users->map(function ($user) {
            return [
                'name' => $user->name,
                'product_count' => $user->countProducts(),
            ];
        });

        return view('pages.admin', compact('users', 'chartData'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
    

        $user->state = User::STATE_BANNED;
        $user->save();
    
        return redirect()->back()->with('success', "User {$user->name} has been banned.");
    
    }
    
    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->state = User::STATE_BLOCKED;
        $user->save();
    
        return redirect()->back()->with('success', "User {$user->name} has been blocked.");
    }
    
    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->state = User::STATE_ACTIVE;
        $user->save();
    
        return redirect()->back()->with('success', "User {$user->name} has been unblocked.");
    }
    
    
    
    
    
    
}
