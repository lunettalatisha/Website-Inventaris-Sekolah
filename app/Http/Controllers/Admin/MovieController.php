<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // ...existing code...

    // returns { data: [activeCount, inactiveCount] }
    public function chart(Request $request)
    {
        $active = Movie::where('is_active', 1)->count();
        $inactive = Movie::where('is_active', 0)->count();

        return response()->json([
            'data' => [$active, $inactive],
        ]);
    }

    // ...existing code...
}
