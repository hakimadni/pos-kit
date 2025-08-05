<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ordersQuery = Order::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * price) as total_sales')
        )
            ->groupBy('product_id')
            ->with('product:id,name');
        // Check for date range parameters
        if ($request->has(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();

            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $orders = $ordersQuery->get();

        return inertia('Dashboard', [
            'productsCount' => \App\Models\Product::count(),
            'categoriesCount' => \App\Models\Category::count(),
            'orders' => $orders,
            // 'cartsCount' => \App\Models\Cart::count(),
        ]);
    }
}
