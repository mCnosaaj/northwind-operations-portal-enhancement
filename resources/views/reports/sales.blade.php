@extends('layouts.app')

@section('title', 'Total Sales Report')
@section('topbar', 'Sales Reporting')
@section('page', 'sales-report')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Reports</div>
            <h1 class="page-title">Total sales report</h1>
            <p class="page-description">Choose an order date range to calculate discounted sales revenue and review every included order.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('reports.inventory') }}">View inventory report</a>
    </div>

    <div class="card surface-card mb-4">
        <div class="card-body">
            <form id="salesReportForm" action="{{ route('reports.sales.data') }}" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-5">
                        <label class="form-label" for="start_date">Start Date <span class="required-mark">*</span></label>
                        <input class="form-control" id="start_date" name="start_date" type="date" value="{{ $startDate }}" max="{{ $endDate }}" required>
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label" for="end_date">End Date <span class="required-mark">*</span></label>
                        <input class="form-control" id="end_date" name="end_date" type="date" value="{{ $endDate }}" min="{{ $startDate }}" max="{{ $endDate }}" required>
                    </div>
                    <div class="col-sm-2 d-grid">
                        <button class="btn btn-primary" type="submit">Generate report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card surface-card report-hero mb-4">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="small text-white-50 text-uppercase fw-bold">Total sales revenue</div>
                    <div class="report-value" id="totalSalesValue">$0.00</div>
                    <div class="small text-white-50" id="salesRangeLabel">Selected date range</div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="small text-white-50 text-uppercase fw-bold">Orders included</div>
                    <div class="h2 fw-bold mb-0" id="orderCountValue">0</div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="small text-white-50 text-uppercase fw-bold">Average order</div>
                    <div class="h2 fw-bold mb-0" id="averageOrderValue">$0.00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card surface-card">
        <div class="card-header">
            <h2 class="h6 mb-1 fw-bold">Orders in selected range</h2>
            <p class="small text-secondary mb-0">Totals include quantity, recorded unit price, and line-item discount.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table w-100" id="salesOrdersTable">
                    <thead><tr><th>Order ID</th><th>Order Date</th><th>Customer</th><th>Ship Country</th><th class="text-end">Order Total</th></tr></thead>
                </table>
            </div>
        </div>
    </div>
@endsection
