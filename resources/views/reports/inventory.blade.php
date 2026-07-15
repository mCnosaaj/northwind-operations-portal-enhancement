@extends('layouts.app')

@section('title', 'Inventory Report')
@section('topbar', 'Inventory Reporting')
@section('page', 'inventory-report')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Reports</div>
            <h1 class="page-title">Inventory report</h1>
            <p class="page-description">Available quantity is calculated as Units In Stock minus the total quantity recorded in order details.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('reports.sales') }}">View sales report</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="surface-card metric-card">
                <div class="metric-label">Products listed</div>
                <div class="metric-value">{{ number_format($products->count()) }}</div>
                <div class="metric-note">Complete Northwind catalog</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="surface-card metric-card">
                <div class="metric-label">Units in stock</div>
                <div class="metric-value">{{ number_format($products->sum('UnitsInStock')) }}</div>
                <div class="metric-note">Current recorded stock</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="surface-card metric-card">
                <div class="metric-label">Total ordered</div>
                <div class="metric-value">{{ number_format($products->sum('TotalOrdered')) }}</div>
                <div class="metric-note">Quantity across order details</div>
            </div>
        </div>
    </div>

    <div class="card surface-card">
        <div class="card-header">
            <h2 class="h6 mb-1 fw-bold">Product availability</h2>
            <p class="small text-secondary mb-0">Search and sort any column to identify the products that need attention.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table w-100" id="inventoryTable">
                    <thead><tr><th>Product ID</th><th>Product Name</th><th class="text-end">Units In Stock</th><th class="text-end">Total Ordered</th><th class="text-end">Available Quantity</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="fw-bold text-primary">{{ $product->ProductID }}</td>
                                <td>{{ $product->ProductName }}</td>
                                <td class="text-end" data-order="{{ $product->UnitsInStock }}">{{ number_format($product->UnitsInStock) }}</td>
                                <td class="text-end" data-order="{{ $product->TotalOrdered }}">{{ number_format($product->TotalOrdered) }}</td>
                                <td class="text-end fw-bold" data-order="{{ $product->AvailableQuantity }}">{{ number_format($product->AvailableQuantity) }}</td>
                                <td>
                                    @if ($product->AvailableQuantity <= 0)
                                        <span class="status-pill danger">Deficit</span>
                                    @elseif ($product->AvailableQuantity <= 10)
                                        <span class="status-pill warning">Low</span>
                                    @else
                                        <span class="status-pill success">Available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
