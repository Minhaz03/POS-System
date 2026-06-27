<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display Analytics.
     */
    public function analytics(): View
    {
        $totalSales = Sale::sum('total_amount');
        $ordersCount = Sale::count();
        $avgOrderValue = $ordersCount > 0 ? $totalSales / $ordersCount : 0;

        // Top selling products
        $topSelling = SaleItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('product_id')
            ->orderBy('qty', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product ? $item->product->name : 'Unknown',
                    'qty' => $item->qty,
                    'revenue' => $item->revenue,
                ];
            })->toArray();

        // Sales by day for the last 7 days
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D'); // Mon, Tue, etc.
            $daySales = Sale::whereDate('created_at', $date)->sum('total_amount');
            $data[] = (float) $daySales;
        }

        $analytics = [
            'total_sales' => $totalSales,
            'orders_count' => $ordersCount,
            'avg_order_value' => round($avgOrderValue, 2),
            'top_selling' => $topSelling,
            'sales_by_day' => [
                'labels' => $labels,
                'data' => $data,
            ]
        ];

        return view('dashboard.analytics', compact('analytics'));
    }
}
