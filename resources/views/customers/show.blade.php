@extends('layouts.app')

@section('title', $customer->CompanyName)
@section('topbar', 'Customer Details')
@section('page', 'customer-detail')

@section('content')
    <div class="page-heading">
        <div>
            <a class="small fw-semibold" href="{{ route('customers.index') }}">← Back to customers</a>
            <h1 class="page-title mt-2">Customer details</h1>
            <p class="page-description">Review the complete customer profile or switch to edit mode to update it.</p>
        </div>
        <button class="btn btn-primary" id="editButton" type="button">Edit customer</button>
    </div>

    <div class="card surface-card overflow-hidden">
        <div class="detail-banner">
            <div class="detail-id">Customer {{ $customer->CustomerID }}</div>
            <h2 class="h3 fw-bold mt-2 mb-1 position-relative">{{ $customer->CompanyName }}</h2>
            <p class="mb-0 text-white-50 position-relative">{{ $customer->ContactName }} · {{ $customer->Country }}</p>
        </div>
        <div class="card-body p-3 p-md-4">
            <form class="ajax-edit-form" action="{{ route('customers.update', $customer) }}" method="post" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="CustomerID">Customer ID</label>
                        <input class="form-control" id="CustomerID" value="{{ $customer->CustomerID }}" readonly>
                        <div class="form-text">The database identifier cannot be changed.</div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label" for="CompanyName">Company Name <span class="required-mark">*</span></label>
                        <input class="form-control" id="CompanyName" name="CompanyName" value="{{ $customer->CompanyName }}" maxlength="40" required readonly data-editable>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="ContactName">Contact Name <span class="required-mark">*</span></label>
                        <input class="form-control" id="ContactName" name="ContactName" value="{{ $customer->ContactName }}" maxlength="30" required readonly data-editable>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="ContactTitle">Contact Title <span class="required-mark">*</span></label>
                        <input class="form-control" id="ContactTitle" name="ContactTitle" value="{{ $customer->ContactTitle }}" maxlength="30" required readonly data-editable>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="Address">Address <span class="required-mark">*</span></label>
                        <input class="form-control" id="Address" name="Address" value="{{ $customer->Address }}" maxlength="60" required readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="City">City <span class="required-mark">*</span></label>
                        <input class="form-control" id="City" name="City" value="{{ $customer->City }}" maxlength="15" required readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="Region">Region</label>
                        <input class="form-control" id="Region" name="Region" value="{{ $customer->Region }}" maxlength="15" readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="PostalCode">Postal Code <span class="required-mark">*</span></label>
                        <input class="form-control" id="PostalCode" name="PostalCode" value="{{ $customer->PostalCode }}" maxlength="10" required readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="Country">Country <span class="required-mark">*</span></label>
                        <input class="form-control" id="Country" name="Country" value="{{ $customer->Country }}" maxlength="15" required readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="Phone">Phone <span class="required-mark">*</span></label>
                        <input class="form-control" id="Phone" name="Phone" value="{{ $customer->Phone }}" maxlength="24" required readonly data-editable>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="Fax">Fax</label>
                        <input class="form-control" id="Fax" name="Fax" value="{{ $customer->Fax }}" maxlength="24" readonly data-editable>
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
