<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class JarvisController extends Controller
{
    public function getDashboard(Request $request)
    {
        $subscriptionsBySources = DB::table('api_keys')
            ->select(DB::raw('COUNT(*) AS count, DATE(created_at) AS date, source'))
            ->groupBy('date', 'source')
            ->orderBy('date', 'DESC')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $sendings = DB::table('logs')
            ->select(DB::raw('COUNT(api_key_id) AS `count`, DATE(created_at) AS date'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

//        $latestSubscriptions = DB::table('api_keys')
//            ->select('email', 'status', 'source', 'created_at')
//            ->whereDate('created_at', '>=', Carbon::now()->startOfDay()->subDays(10))
//            ->orderBy('created_at', 'ASC')
//            ->get();

        return view('jarvis.dashboard', compact('subscriptions', 'subscriptionsBySources', 'latestSubscriptions', 'sendings'));
    }
}
