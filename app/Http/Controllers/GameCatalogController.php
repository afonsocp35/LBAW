<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTemplate;
use Illuminate\Support\Facades\DB;

class GameCatalogController extends Controller
{
    public function index()
    {
        $games = Product::with(['_template.images', '_seller']) // Include seller in the query
            ->whereHas('_seller', function ($query) {
                $query->where('state', '!=', 'Banned'); // Exclude banned sellers
            })
            ->get()
            ->map(function ($product) {
                $template = ProductTemplate::find($product->template);
    
                return [
                    'id' => $product->id,
                    'name' => $template ? $template->getName() : 'Unknown',
                    'price' => $product->price,
                    'image' => $template && $template->images()->exists()
                        ? $template->images->first()->path
                        : null,
                ];
            });
    
        $groupedGames = $games->groupBy('name');
    
        return view('catalog', ['games' => $groupedGames]);
    }
    

    public function buy(Request $request)
    {
        $platform = $request->input('platform');
        
        return redirect()->back()->with('success', "You have successfully purchased the game for platform: $platform");
    }

    public function filterByPlatform($platformName)
    {
        $existingPlatforms = $this->getPlatforms();
        if (!in_array($platformName, $existingPlatforms)) {
            return redirect()->back()->withErrors('Invalid platform selected.');
        }

        $games = Product::with(['_template.images', '_seller'])
            ->whereHas('platforms', function ($query) use ($platformName) {
                $query->where('platform_name', $platformName);
            })
            ->whereHas('_seller', function ($query) {
                $query->where('state', '!=', 'Banned');
            })
            ->get()
            ->map(function ($product) {
                $template = ProductTemplate::find($product->template);
    
                return [
                    'id' => $product->id,
                    'name' => $template ? $template->getName() : 'Unknown',
                    'price' => $product->price,
                    'image' => $template && $template->images()->exists() 
                        ? $template->images->first()->path
                        : null,
                ];
            });
    
        $groupedGames = $games->groupBy('name');
    
        return view('platform.show', [
            'platform' => $platformName,
            'games' => $groupedGames,
        ]);
    }
    

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        if (empty($search)) {
            $games = Product::with(['_template.images', '_seller'])
                ->whereHas('_seller', function ($query) {
                    $query->where('state', '!=', 'Banned');
                })
                ->get()
                ->map(function ($product) {
                    $template = ProductTemplate::find($product->template);
    
                    return [
                        'id' => $product->id,
                        'name' => $template ? $template->name : 'Unknown',
                        'price' => $product->price,
                        'image' => $template && $template->images()->exists() 
                            ? $template->images->first()->path
                            : null,
                    ];
                });
    
            $groupedGames = $games->groupBy('name');
    
            return view('catalog', ['games' => $groupedGames, 'search' => $search]);
        }
    

        $tsQuery = implode(' & ', array_map(fn($word) => $word . ':*', explode(' ', $search)));
        
        $templates = ProductTemplate::whereRaw(
            "to_tsvector('english', name || ' ' || developer) @@ to_tsquery('english', ?)",
            [$tsQuery]
        )->get();
    
        $games = Product::with(['_template.images', '_seller'])
            ->whereIn('template', $templates->pluck('id'))
            ->whereHas('_seller', function ($query) {
                $query->where('state', '!=', 'Banned');
            })
            ->get()
            ->map(function ($product) {
                $template = ProductTemplate::find($product->template);
    
                return [
                    'id' => $product->id,
                    'name' => $template ? $template->name : 'Unknown',
                    'price' => $product->price,
                    'image' => $template && $template->images()->exists() 
                        ? $template->images->first()->path
                        : null,
                ];
            });
    
        $groupedGames = $games->groupBy('name');
    
        return view('catalog', ['games' => $groupedGames, 'search' => $search]);
    }
    
    
    private function getPlatforms()
    {
        
        $result = DB::select("SELECT unnest(enum_range(NULL::platform)) AS value");
        return array_map(fn($row) => $row->value, $result);
    }
    
    
}
