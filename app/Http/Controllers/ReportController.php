<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private const SALES_EXPRESSION = 'od.UnitPrice * od.Quantity * (1 - od.Discount)';

    public function sales()
    {
        $range = DB::table('ORDERS')
            ->selectRaw('MIN(DATE(OrderDate)) as min_date, MAX(DATE(OrderDate)) as max_date')
            ->first();

        return view('reports.sales', [
            'startDate' => $range->min_date,
            'endDate' => $range->max_date,
        ]);
    }

    public function salesData(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->endOfDay();

        $orders = DB::table('ORDERS as o')
            ->leftJoin('CUSTOMERS as c', 'c.CustomerID', '=', 'o.CustomerID')
            ->join('ORDER_DETAILS as od', 'od.OrderID', '=', 'o.OrderID')
            ->whereBetween('o.OrderDate', [$start, $end])
            ->select('o.OrderID', 'o.OrderDate', 'c.CompanyName', 'o.ShipCountry')
            ->selectRaw('SUM('.self::SALES_EXPRESSION.') as TotalPrice')
            ->groupBy('o.OrderID', 'o.OrderDate', 'c.CompanyName', 'o.ShipCountry')
            ->orderByDesc('o.OrderDate')
            ->get()
            ->map(fn ($order) => [
                'order_id' => $order->OrderID,
                'order_date' => Carbon::parse($order->OrderDate)->format('M d, Y'),
                'company_name' => $order->CompanyName ?? 'Guest customer',
                'ship_country' => $order->ShipCountry,
                'total_price' => round((float) $order->TotalPrice, 2),
            ]);

        $total = round($orders->sum('total_price'), 2);
        $count = $orders->count();

        return response()->json([
            'summary' => [
                'total_sales' => $total,
                'order_count' => $count,
                'average_order' => $count > 0 ? round($total / $count, 2) : 0,
                'start_date' => $start->format('M d, Y'),
                'end_date' => $end->format('M d, Y'),
            ],
            'data' => $orders,
        ]);
    }

    public function inventory()
    {
        $ordered = DB::table('ORDER_DETAILS')
            ->select('ProductID')
            ->selectRaw('SUM(Quantity) as TotalOrdered')
            ->groupBy('ProductID');

        $products = DB::table('PRODUCTS as p')
            ->leftJoinSub($ordered, 'ordered', 'ordered.ProductID', '=', 'p.ProductID')
            ->select('p.ProductID', 'p.ProductName', 'p.UnitsInStock')
            ->selectRaw('COALESCE(ordered.TotalOrdered, 0) as TotalOrdered')
            ->selectRaw('(p.UnitsInStock - COALESCE(ordered.TotalOrdered, 0)) as AvailableQuantity')
            ->orderBy('p.ProductID')
            ->get();

        return view('reports.inventory', compact('products'));
    }
}
