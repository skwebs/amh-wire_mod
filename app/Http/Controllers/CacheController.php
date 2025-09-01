<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class CacheController extends Controller
// {
//     //
// }


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clearCache(Request $request)
    {
        // Clear application cache
        // Cache::flush();

        // Clear route cache
        Artisan::call('route:clear');

        // // Clear config cache
        // Artisan::call('config:clear');

        // // Clear view cache
        // Artisan::call('view:clear');

        // // Clear compiled class cache
        // Artisan::call('clear-compiled');

        // Handle goback parameter
        $goback = $request->query('goback');
        if ($goback) {
            if ($goback === '1') {
                return redirect()->back()->with('message', 'All caches cleared successfully!');
            } else {
                return redirect($goback)->with('message', 'All caches cleared successfully!');
            }
        }

        return response()->json(['message' => 'All caches cleared successfully!']);
    }
}
