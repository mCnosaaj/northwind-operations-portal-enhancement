<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index', ['customerCount' => Customer::count()]);
    }

    public function data(): JsonResponse
    {
        $customers = Customer::query()
            ->select('CustomerID', 'CompanyName', 'ContactName', 'ContactTitle', 'Phone', 'Country')
            ->orderBy('CompanyName')
            ->get();

        return response()->json(['data' => $customers]);
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'CompanyName' => ['required', 'string', 'max:40'],
            'ContactName' => ['required', 'string', 'max:30'],
            'ContactTitle' => ['required', 'string', 'max:30'],
            'Address' => ['required', 'string', 'max:60'],
            'City' => ['required', 'string', 'max:15'],
            'Region' => ['nullable', 'string', 'max:15'],
            'PostalCode' => ['required', 'string', 'max:10'],
            'Country' => ['required', 'string', 'max:15'],
            'Phone' => ['required', 'string', 'max:24'],
            'Fax' => ['nullable', 'string', 'max:24'],
        ]);

        $customer->fill($validated)->save();

        $savedCustomer = $customer->fresh()->only([
            'CustomerID',
            'CompanyName',
            'ContactName',
            'ContactTitle',
            'Address',
            'City',
            'Region',
            'PostalCode',
            'Country',
            'Phone',
            'Fax',
        ]);

        return response()->json([
            'message' => 'Customer details saved successfully.',
            'customer' => $savedCustomer,
        ]);
    }
}
