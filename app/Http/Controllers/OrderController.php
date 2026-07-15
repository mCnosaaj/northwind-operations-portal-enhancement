<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index()
    {
        $customers = Customer::query()
            ->withCount('orders')
            ->orderBy('CompanyName')
            ->get(['CustomerID', 'CompanyName', 'ContactName']);

        return view('orders.index', compact('customers'));
    }

    public function customerOrders(Customer $customer): JsonResponse
    {
        $orders = $customer->orders()
            ->with(['details.product:ProductID,ProductName'])
            ->orderByDesc('OrderDate')
            ->orderByDesc('OrderID')
            ->get()
            ->map(function ($order) {
                $products = $order->details->map(function ($detail) {
                    $subtotal = (float) $detail->UnitPrice
                        * (int) $detail->Quantity
                        * (1 - (float) $detail->Discount);

                    return [
                        'product_name' => $detail->product?->ProductName ?? 'Unknown product',
                        'quantity' => (int) $detail->Quantity,
                        'unit_price' => (float) $detail->UnitPrice,
                        'discount' => (float) $detail->Discount,
                        'subtotal' => round($subtotal, 2),
                    ];
                });

                return [
                    'order_id' => $order->OrderID,
                    'order_date' => $order->OrderDate?->format('M d, Y'),
                    'ship_name' => $order->ShipName,
                    'ship_city' => $order->ShipCity,
                    'total_price' => round($products->sum('subtotal'), 2),
                    'products' => $products,
                ];
            });

        return response()->json([
            'customer' => [
                'id' => $customer->CustomerID,
                'company_name' => $customer->CompanyName,
            ],
            'orders' => $orders,
        ]);
    }
}
