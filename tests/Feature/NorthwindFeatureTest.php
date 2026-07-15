<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Tests\TestCase;

class NorthwindFeatureTest extends TestCase
{
    public function test_main_pages_render_from_the_northwind_database(): void
    {
        $this->get('/')->assertRedirect('/reports/inventory');
        $this->get('/dashboard')->assertOk()->assertSee('Northwind at a glance');
        $this->get('/customers')->assertOk()->assertSee('Customer directory');
        $this->get('/customers/ALFKI')->assertOk()->assertSee('Alfreds Futterkiste');
        $this->get('/products')->assertOk()->assertSee('Product catalog');
        $this->get('/products/1')->assertOk()->assertSee('Chai');
        $this->get('/orders')->assertOk()->assertSee('Customer order history');
        $this->get('/reports/sales')->assertOk()->assertSee('Total sales report');
        $this->get('/reports/inventory')->assertOk()->assertSee('Inventory report');
    }

    public function test_customer_and_product_ajax_lists_return_real_records(): void
    {
        $this->getJson('/customers/data')
            ->assertOk()
            ->assertJsonCount(91, 'data')
            ->assertJsonPath('data.0.CustomerID', 'ALFKI');

        $this->getJson('/products/data')
            ->assertOk()
            ->assertJsonCount(77, 'data')
            ->assertJsonPath('data.0.ProductID', 1)
            ->assertJsonStructure(['data' => [['ProductID', 'ProductName', 'category', 'supplier']]]);
    }

    public function test_customer_orders_include_nested_products_and_totals(): void
    {
        $this->getJson('/orders/customer/VINET')
            ->assertOk()
            ->assertJsonPath('customer.id', 'VINET')
            ->assertJsonStructure([
                'orders' => [[
                    'order_id',
                    'order_date',
                    'ship_name',
                    'ship_city',
                    'total_price',
                    'products' => [['product_name', 'quantity', 'unit_price', 'discount', 'subtotal']],
                ]],
            ]);
    }

    public function test_sales_report_validates_dates_and_returns_calculated_revenue(): void
    {
        $this->getJson('/reports/sales/data?start_date=1994-08-04&end_date=1994-08-04')
            ->assertOk()
            ->assertJsonPath('summary.order_count', 1)
            ->assertJsonPath('summary.total_sales', 440);

        $this->getJson('/reports/sales/data?start_date=1995-01-02&end_date=1994-01-01')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('end_date');
    }

    public function test_update_endpoints_reject_missing_required_fields_without_writing(): void
    {
        $token = 'northwind-test-token';

        $this->withSession(['_token' => $token])->putJson('/customers/ALFKI', ['_token' => $token])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['CompanyName', 'ContactName', 'Country', 'Phone']);

        $this->withSession(['_token' => $token])->putJson('/products/1', ['_token' => $token])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['ProductName', 'CategoryID', 'SupplierID', 'UnitPrice', 'UnitsInStock']);
    }

    public function test_valid_ajax_updates_save_successfully_without_changing_fixture_values(): void
    {
        $token = 'northwind-valid-save-token';
        $customer = Customer::findOrFail('ALFKI');

        $customerPayload = $customer->only([
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
        $customerPayload['_token'] = $token;

        $this->withSession(['_token' => $token])->putJson('/customers/ALFKI', $customerPayload)
            ->assertOk()
            ->assertJsonPath('message', 'Customer details saved successfully.')
            ->assertJsonPath('customer.CompanyName', 'Alfreds Futterkiste');

        $product = Product::findOrFail(1);
        $productPayload = $product->only([
            'ProductName',
            'SupplierID',
            'CategoryID',
            'QuantityPerUnit',
            'UnitPrice',
            'UnitsInStock',
            'UnitsOnOrder',
            'ReorderLevel',
            'Discontinued',
        ]);
        $productPayload['_token'] = $token;

        $this->withSession(['_token' => $token])->putJson('/products/1', $productPayload)
            ->assertOk()
            ->assertJsonPath('message', 'Product details saved successfully.')
            ->assertJsonPath('product.ProductName', 'Chai');
    }
}
