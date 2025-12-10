<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{
    // ...existing code...

    // returns { labels: [...], data: [...] }
    public function chart(Request $request)
    {
        $days = 7;
        $labels = [];
        $counts = [];

        // build labels for last N days
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i)->toDateString();
            $labels[] = $d;
            $counts[$d] = 0;
        }

        // get counts grouped by date
        $rows = Ticket::selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::today()->subDays($days - 1)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($rows as $r) {
            $counts[$r->date] = (int)$r->total;
        }

        $data = array_values($counts);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    // ...existing code...
}
