<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    /**
     * Display Custom Orders.
     */
    public function customOrders(): View
    {
        $dbOrders = \App\Models\CustomOrder::orderBy('delivery_date', 'asc')->get();
        $orders = $dbOrders->map(function ($order) {
            return [
                'id' => $order->order_number,
                'customer' => $order->customer_name,
                'details' => $order->details,
                'delivery_date' => $order->delivery_date->format('Y-m-d'),
                'price' => (float) $order->total_price,
                'advance' => (float) $order->advance_payment,
                'status' => $order->status,
            ];
        })->toArray();

        return view('dashboard.custom-orders', compact('orders'));
    }
}
