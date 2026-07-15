<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['productCount' => Product::count()]);
    }

    public function data(): JsonResponse
    {
        $products = Product::query()
            ->with([
                'category:CategoryID,CategoryName',
                'supplier:SupplierID,CompanyName',
            ])
            ->orderBy('ProductID')
            ->get([
                'ProductID',
                'ProductName',
                'CategoryID',
                'SupplierID',
                'QuantityPerUnit',
                'UnitPrice',
                'UnitsInStock',
                'UnitsOnOrder',
            ]);

        return response()->json(['data' => $products]);
    }

    public function show(Product $product)
    {
        $categories = Category::query()->orderBy('CategoryName')->get(['CategoryID', 'CategoryName']);
        $suppliers = Supplier::query()->orderBy('CompanyName')->get(['SupplierID', 'CompanyName']);

        return view('products.show', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'ProductName' => ['required', 'string', 'max:40'],
            'CategoryID' => ['required', 'integer', 'exists:CATEGORIES,CategoryID'],
            'SupplierID' => ['required', 'integer', 'exists:SUPPLIERS,SupplierID'],
            'QuantityPerUnit' => ['required', 'string', 'max:20'],
            'UnitPrice' => ['required', 'numeric', 'min:0', 'max:999999999999.9999'],
            'UnitsInStock' => ['required', 'integer', 'min:0'],
            'UnitsOnOrder' => ['required', 'integer', 'min:0'],
            'ReorderLevel' => ['required', 'integer', 'min:0'],
            'Discontinued' => ['required', 'boolean'],
        ]);

        $product->fill($validated)->save();

        $savedProduct = $product->fresh()->load([
            'category:CategoryID,CategoryName',
            'supplier:SupplierID,CompanyName',
        ]);

        return response()->json([
            'message' => 'Product details saved successfully.',
            'product' => [
                ...$savedProduct->only([
                    'ProductID',
                    'ProductName',
                    'SupplierID',
                    'CategoryID',
                    'QuantityPerUnit',
                    'UnitPrice',
                    'UnitsInStock',
                    'UnitsOnOrder',
                    'ReorderLevel',
                    'Discontinued',
                ]),
                'category' => $savedProduct->category,
                'supplier' => $savedProduct->supplier,
            ],
        ]);
    }
}
