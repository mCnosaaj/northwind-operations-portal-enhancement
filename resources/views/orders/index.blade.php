@extends('layouts.app')

@section('title', 'Customer Orders')
@section('topbar', 'Orders by Customer')
@section('page', 'orders')

@section('content')
    <div class="page-heading">
        <div>
            <div class="eyebrow">Orders display</div>
            <h1 class="page-title">Customer order history</h1>
            <p class="page-description">Expand a customer to load their orders and nested product line items. Click the same customer again to collapse it.</p>
        </div>
    </div>

    <div class="card surface-card">
        <div class="card-header">
            <div class="row g-3 align-items-center">
                <div class="col-md">
                    <h2 class="h6 mb-1 fw-bold">Customers and orders</h2>
                    <p class="small text-secondary mb-0">Order data is loaded with AJAX only when a customer is expanded.</p>
                </div>
                <div class="col-md-5 col-xl-4">
                    <label class="visually-hidden" for="customerAccordionSearch">Search customers</label>
                    <input class="form-control" id="customerAccordionSearch" type="search" placeholder="Search company or contact...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="accordion" id="customerOrdersAccordion" data-url="{{ route('orders.customer', '__ID__') }}">
                @foreach ($customers as $customer)
                    @php($searchText = strtolower($customer->CompanyName.' '.$customer->ContactName.' '.$customer->CustomerID))
                    <div class="accordion-item customer-accordion-item" data-search="{{ $searchText }}">
                        <h2 class="accordion-header" id="heading-{{ $customer->CustomerID }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#orders-{{ $customer->CustomerID }}" aria-expanded="false" aria-controls="orders-{{ $customer->CustomerID }}">
                                <span class="customer-avatar">{{ strtoupper(substr($customer->CompanyName, 0, 2)) }}</span>
                                <span class="flex-grow-1">
                                    <span class="d-block">{{ $customer->CompanyName }}</span>
                                    <span class="customer-contact">{{ $customer->ContactName ?? 'No contact name' }}</span>
                                </span>
                                <span class="badge rounded-pill text-bg-light me-2">{{ $customer->orders_count }} orders</span>
                            </button>
                        </h2>
                        <div class="accordion-collapse collapse" id="orders-{{ $customer->CustomerID }}" data-bs-parent="#customerOrdersAccordion" data-customer-id="{{ $customer->CustomerID }}" aria-labelledby="heading-{{ $customer->CustomerID }}">
                            <div class="accordion-body"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="empty-state d-none" id="accordionNoResults"><strong>No customers match your search.</strong></div>
        </div>
    </div>
@endsection
