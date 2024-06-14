<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function salesData()
    {
        // Total orders
        $totalOrders = Order::count();
    
        // Total sales (total_cost)
        $totalSales = Order::sum('total_cost');
    
        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
    
        // Sales per month (example for the last 12 months)
        $salesPerMonth = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('DATE_FORMAT(created_at, "%b") as month_name'), // Format nama bulan
            DB::raw('SUM(total_cost) as total_sales')
        )
        ->groupBy('year', 'month', 'month_name') // Group by month_name for label
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
    
        return view('pages.dashboard', compact('totalOrders', 'totalSales', 'ordersByStatus', 'salesPerMonth'));
    }
}
