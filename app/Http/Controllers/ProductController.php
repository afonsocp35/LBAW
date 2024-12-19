<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTemplate;
use App\Models\ProductPlatform;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\ShoppingCart;
use App\Models\OrderItem;
use App\Models\Notification;
use Illuminate\View\View;
use App\Notifications\ProductPriceChangeNotification;
use App\Models\Notifications;

class ProductController extends Controller
{
    /**
     * Display a product page.
     */

    public function show(string $id): View
    {
        // check if product exists?

        $product = Product::findOrFail($id);
        
        if(Auth::user() == null && $product->_seller->state == 'Banned'){
            abort(404, 'No available products for this game.');
        }

        if ($product->_seller->state == 'Banned' && !Auth::user()->is_admin) {
            abort(404, 'No available products for this game.');
        }




        // check if the current user can see (show) the card.
        //$this->authorize('show', $card);

        return view('pages.product', [
            'product' => $product
        ]);
    }

    public function index()
    {
        $products = Product::with('_template', 'platforms')->get();
        $notifications = Notification::all();
        return view('pages.product-list', compact('products', 'notifications'));
    }
    

    public function create()
    {
        // Get all product templates that the seller can choose from
        $templates = ProductTemplate::all();
        $platforms = ['PC', 'MacOS', 'Xbox', 'Playstation', 'Switch']; // Available platforms

        return view('pages.product-create', compact('templates', 'platforms'));
    }

    // Store the new product
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'template_option' => 'required|in:existing,new',
        ]);
    
        // Determine which template to use
        if ($request->template_option == 'existing') {
            $request->validate(
            ['template_id' => 'required|exists:ProductTemplate,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'platforms' => 'required|array',
            'platforms.*' => 'in:PC,MacOS,Xbox,Playstation,Switch']);
            $templateId = $request->template_id;
        } else {
            $request->validate([
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'platforms' => 'required|array',
                'platforms.*' => 'in:PC,MacOS,Xbox,Playstation,Switch',
                'name' => 'required|string|max:255',
                'developer' => 'required|string|max:255',
                'description' => 'required|string',
                'images' => 'nullable|array', // Validate that images are an array, if present
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            
            // Create the new template
            $template = new ProductTemplate();
            $template->name = $request->name;
            $template->developer = $request->developer;
            $template->description = $request->description;
            $template->save();
    
            // Handle image uploads
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $index = 1;
                foreach ($images as $image) {

                    $imageName =  $template->name . '_' . $index . '.' . $image->extension();
                    $image->move(public_path('images'), $imageName);
                    
                    // Save the image path to the template_images table
                    ProductImage::create([
                        'template' => $template->id, // Reference the template ID
                        'index' => $index++,        // Increment the index for each image
                        'path' => 'images/' . $imageName,       // Store the image path
                    ]);
                }
            }
    
            // Use the newly created template's ID
            $templateId = $template->id;
        }
    
        // Create the new product
        $product = new Product();
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->seller = Auth::id(); // The current logged-in user (seller)
        $product->template = $templateId;
        $product->save();
    
        // Attach platforms to the product
        foreach ($request->platforms as $platform) {
            ProductPlatform::create([
                'product' => $product->id,
                'platform_name' => $platform
            ]);
        }
    
        // Redirect back with success message
        return redirect()->route('admin.index')->with('status', 'Product added successfully!');
    }

    public function destroy(string $id)
    {
        
        $product = Product::findOrFail($id);
        $product->platforms()->delete();
        Wishlist::where('product', $id)->delete();
        ShoppingCart::where('product', $id)->delete();
        OrderItem::where('product', $id)->delete();
        Notification::where('product', $id)->delete();
        $product->delete();


    
        return redirect()->route('product.index')->with('status', 'Product removed successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $sellers = User::all();
    
        return view('pages.product-edit', compact('product', 'sellers'));
    }
    
    

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'developer' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'seller_id' => 'required|exists:users,id',
        ]);
    
        // Update product details
        $product->_template->name = $request->name;
        $product->_template->developer = $request->developer;
        $product->_template->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->seller = $request->seller_id;
    
        $product->_template->save();
        $product->save();
        $user = User::findOrFail($request->seller_id);
        $user->notify(new ProductPriceChangeNotification($product, $user));
        return redirect()->route('product.index')->with('status', 'Product updated successfully!');
    }
    
    
    
    
}
