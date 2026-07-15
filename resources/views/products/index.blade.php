@extends('layouts.app')

@section('title', 'Products')
@section('topbar', 'Product Management')
@section('page', 'products')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Product management</div>
            <h1 class="page-title">Product catalog</h1>
            <p class="page-description">Search by product name or category, sort inventory fields, and click a row to edit the product.</p>
        </div>
        <span class="status-pill success">{{ number_format($productCount) }} catalog products</span>
    </div>

    <div class="card surface-card">
        <div class="card-header">
            <h2 class="h6 mb-1 fw-bold">All products</h2>
            <p class="small text-secondary mb-0">The search box matches product names, categories, suppliers, and other visible fields.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table clickable-table w-100" id="productsTable"
                       data-source="{{ route('products.data') }}"
                       data-show-url="{{ route('products.show', '__ID__') }}">
                    <thead>
                        <tr><th>Product ID</th><th>Product Name</th><th>Category</th><th>Supplier</th><th>Quantity Per Unit</th><th class="text-end">Unit Price</th><th class="text-end">In Stock</th><th class="text-end">On Order</th><th class="text-end">Action</th></tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
