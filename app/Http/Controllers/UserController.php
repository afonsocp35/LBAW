<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Notification;

class UserController extends Controller
{
    public function show(string $id)
    {
        // Allow access if the authenticated user is the owner or an admin
        if (Auth::id() != $id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized'); 
        }

        $user = User::findOrFail($id);

        return view('pages.profile', compact('user'));
    }

    public function edit(string $id)
    {
        // Allow access if the authenticated user is the owner or an admin
        if (Auth::id() != $id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized'); 
        }

        $user = User::findOrFail($id);

        return view('pages.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Allow access if the authenticated user is the owner or an admin
        if (Auth::id() != $id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized'); 
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->address = $request->address;

        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('images'), $imageName);
            $user->profile_picture = 'images/' . $imageName;
        }

        $user->save();

        return redirect()->route('profile.show', ['id' => $user->id])
            ->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
    

        $user->state = User::STATE_BANNED;
        $user->save();

        Auth::logout();
        return redirect()->route('login')
            ->withSuccess('Your account has been removed!');
    
    }


    
}
