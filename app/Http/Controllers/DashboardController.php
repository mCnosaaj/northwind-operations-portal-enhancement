<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $salesExpression = 'od.UnitPrice * od.Quantity * (1 - od.Discount)';

        $metrics = [
            'customers' => DB::table('CUSTOMERS')->count(),
            'products' => DB::table('PRODUCTS')->count(),
            'orders' => DB::table('ORDERS')->count(),
            'sales' => (float) DB::table('ORDER_DETAILS as od')->sum(DB::raw($salesExpression)),
        ];

        $recentOrders = DB::table('ORDERS as o')
            ->leftJoin('CUSTOMERS as c', 'c.CustomerID', '=', 'o.CustomerID')
            ->join('ORDER_DETAILS as od', 'od.OrderID', '=', 'o.OrderID')
            ->select('o.OrderID', 'o.OrderDate', 'o.ShipCity', 'c.CompanyName')
            ->selectRaw("SUM($salesExpression) as TotalPrice")
            ->groupBy('o.OrderID', 'o.OrderDate', 'o.ShipCity', 'c.CompanyName')
            ->orderByDesc('o.OrderDate')
            ->orderByDesc('o.OrderID')
            ->limit(6)
            ->get();

        return view('dashboard', compact('metrics', 'recentOrders'));
    }
}
