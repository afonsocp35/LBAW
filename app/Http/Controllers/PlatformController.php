<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{

    public function index()
    {

        $platforms = $this->getPlatforms();

        return view('pages.platform-index', compact('platforms'));
    }


    public function create()
    {
        return view('pages.platform-create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'platform_name' => ['required', 'string', 'max:255'],
        ]);
    
        $platformName = $request->input('platform_name');
    
        // Check if the platform already exists in the ENUM type
        $existingPlatforms = $this->getPlatforms();
        if (in_array($platformName, $existingPlatforms)) {
            return redirect()->route('platform.index')->with('error', 'Platform already exists.');
        }
    
        try {
            DB::statement("ALTER TYPE platform ADD VALUE '" . pg_escape_string($platformName) . "'");
        } catch (\Exception $e) {
            return redirect()->route('platform.index')->with('error', 'Failed to add the platform: ' . $e->getMessage());
        }
        
        return redirect()->route('platform.index')->with('status', 'Platform added successfully.');
    }


    public function destroy(Request $request, $platformName)
    {

        return redirect()->route('platform.index')->with('error', 'Removing platforms is not supported in PostgreSQL ENUM types.');
    }


    private function getPlatforms()
    {
        
        $result = DB::select("SELECT unnest(enum_range(NULL::platform)) AS value");
        return array_map(fn($row) => $row->value, $result);
    }
}
