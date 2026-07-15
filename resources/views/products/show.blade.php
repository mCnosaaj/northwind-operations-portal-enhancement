@extends('layouts.app')

@section('title', $product->ProductName)
@section('topbar', 'Product Details')
@section('page', 'product-detail')

@section('content')
    <div class="page-heading">
        <div>
            <a class="small fw-semibold" href="{{ route('products.index') }}">← Back to products</a>
            <h1 class="page-title mt-2">Product details</h1>
            <p class="page-description">Review pricing, supplier, category, and inventory information, then edit when needed.</p>
        </div>
        <button class="btn btn-primary" id="editButton" type="button">Edit product</button>
    </div>

    <div class="card surface-card overflow-hidden">
        <div class="detail-banner">
            <div class="detail-id">Product #{{ $product->ProductID }}</div>
            <h2 class="h3 fw-bold mt-2 mb-1 position-relative">{{ $product->ProductName }}</h2>
            <p class="mb-0 text-white-50 position-relative">{{ $product->UnitsInStock }} units currently in stock</p>
        </div>
        <div class="card-body p-3 p-md-4">
            <form class="ajax-edit-form" action="{{ route('products.update', $product) }}" method="post" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="ProductID">Product ID</label>
                        <input class="form-control" id="ProductID" value="{{ $product->ProductID }}" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label" for="ProductName">Product Name <span class="required-mark">*</span></label>
                        <input class="form-control" id="ProductName" name="ProductName" value="{{ $product->ProductName }}" maxlength="40" required readonly data-editable>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="CategoryID">Category <span class="required-mark">*</span></label>
                        <select class="form-select" id="CategoryID" name="CategoryID" required disabled data-editable>
                            @foreach ($categories as $category)
                                <option value="{{ $category->CategoryID }}" @selected($product->CategoryID == $category->CategoryID)>{{ $category->CategoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="SupplierID">Supplier <span class="required-mark">*</span></label>
                        <select class="form-select" id="SupplierID" name="SupplierID" required disabled data-editable>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->SupplierID }}" @selected($product->SupplierID == $supplier->SupplierID)>{{ $supplier->CompanyName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="QuantityPerUnit">Quantity Per Unit <span class="required-mark">*</span></label>
                        <input class="form-control" id="QuantityPerUnit" name="QuantityPerUnit" value="{{ $product->QuantityPerUnit }}" maxlength="20" required readonly data-editable>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="UnitPrice">Unit Price <span class="required-mark">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input class="form-control" id="UnitPrice" name="UnitPrice" type="number" value="{{ number_format((float) $product->UnitPrice, 2, '.', '') }}" min="0" step="0.01" required readonly data-editable>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="UnitsInStock">Units In Stock <span class="required-mark">*</span></label>
                        <input class="form-control" id="UnitsInStock" name="UnitsInStock" type="number" value="{{ $product->UnitsInStock }}" min="0" required readonly data-editable>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="UnitsOnOrder">Units On Order <span class="required-mark">*</span></label>
                        <input class="form-control" id="UnitsOnOrder" name="UnitsOnOrder" type="number" value="{{ $product->UnitsOnOrder }}" min="0" required readonly data-editable>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="ReorderLevel">Reorder Level <span class="required-mark">*</span></label>
                        <input class="form-control" id="ReorderLevel" name="ReorderLevel" type="number" value="{{ $product->ReorderLevel }}" min="0" required readonly data-editable>
                    </div>
                    <div class="col-12">
                        <input name="Discontinued" type="hidden" value="0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" id="Discontinued" name="Discontinued" type="checkbox" value="1" @checked($product->Discontinued) disabled data-editable>
                            <label class="form-check-label fw-semibold" for="Discontinued">Product is discontinued</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex d-none justify-content-end gap-2 mt-4" id="saveActions">
                    <button class="btn btn-light" id="cancelEdit" type="button">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
