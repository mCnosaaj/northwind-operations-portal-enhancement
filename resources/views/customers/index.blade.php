@extends('layouts.app')

@section('title', 'Customers')
@section('topbar', 'Customer Management')
@section('page', 'customers')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Customer management</div>
            <h1 class="page-title">Customers</h1>
            <p class="page-description">Search, sort, and open any customer record. Click anywhere on a row to view full details.</p>
        </div>
        <span class="status-pill success">{{ number_format($customerCount) }} customer records</span>
    </div>

    <div class="card surface-card">
        <div class="card-header">
            <h2 class="h6 mb-1 fw-bold">Customer directory</h2>
            <p class="small text-secondary mb-0">Use the table controls to search or sort by any visible field.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table clickable-table w-100" id="customersTable"
                       data-source="{{ route('customers.data') }}"
                       data-show-url="{{ route('customers.show', '__ID__') }}">
                    <thead>
                        <tr><th>Customer ID</th><th>Company Name</th><th>Contact Name</th><th>Contact Title</th><th>Phone</th><th>Country</th><th class="text-end">Action</th></tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
