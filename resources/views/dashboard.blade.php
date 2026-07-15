@extends('layouts.app')

@section('title', 'Dashboard')
@section('topbar', 'Operations Dashboard')
@section('page', 'dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Overview</div>
            <h1 class="page-title">Northwind at a glance</h1>
            <p class="page-description">Manage customer relationships, product inventory, orders, and reporting from one connected workspace.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('reports.sales') }}">View sales report</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="surface-card metric-card" style="--metric-color:#176b45">
                <div class="metric-label">Customers</div>
                <div class="metric-value">{{ number_format($metrics['customers']) }}</div>
                <div class="metric-note">Active customer records</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="surface-card metric-card" style="--metric-color:#3f8f68">
                <div class="metric-label">Products</div>
                <div class="metric-value">{{ number_format($metrics['products']) }}</div>
                <div class="metric-note">Products in the catalog</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="surface-card metric-card" style="--metric-color:#45534b">
                <div class="metric-label">Orders</div>
                <div class="metric-value">{{ number_format($metrics['orders']) }}</div>
                <div class="metric-note">Orders across all customers</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="surface-card metric-card" style="--metric-color:#0e4f34">
                <div class="metric-label">Total sales</div>
                <div class="metric-value">${{ number_format($metrics['sales'], 2) }}</div>
                <div class="metric-note">Revenue after line discounts</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card surface-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h6 mb-1 fw-bold">Recent orders</h2>
                        <p class="small text-secondary mb-0">Latest transactions in the Northwind database</p>
                    </div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('orders.index') }}">Explore orders</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Order ID</th><th>Customer</th><th>Date</th><th>Ship City</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $order->OrderID }}</td>
                                    <td>{{ $order->CompanyName ?? 'Guest customer' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->OrderDate)->format('M d, Y') }}</td>
                                    <td>{{ $order->ShipCity ?? '—' }}</td>
                                    <td class="text-end fw-semibold">${{ number_format($order->TotalPrice, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card surface-card h-100">
                <div class="card-header">
                    <h2 class="h6 mb-1 fw-bold">Quick actions</h2>
                    <p class="small text-secondary mb-0">Jump directly into common tasks</p>
                </div>
                <div class="card-body d-grid gap-2">
                    <a class="quick-link" href="{{ route('customers.index') }}"><span><strong>Find a customer</strong><small class="d-block text-secondary">Search and edit customer records</small></span><span>→</span></a>
                    <a class="quick-link" href="{{ route('products.index') }}"><span><strong>Manage products</strong><small class="d-block text-secondary">Review prices and stock levels</small></span><span>→</span></a>
                    <a class="quick-link" href="{{ route('reports.sales') }}"><span><strong>Run sales report</strong><small class="d-block text-secondary">Choose any order date range</small></span><span>→</span></a>
                    <a class="quick-link" href="{{ route('reports.inventory') }}"><span><strong>Check inventory</strong><small class="d-block text-secondary">Compare stock against ordered units</small></span><span>→</span></a>
                </div>
            </div>
        </div>
    </div>
@endsection
