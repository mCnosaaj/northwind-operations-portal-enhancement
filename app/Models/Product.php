<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'PRODUCTS';

    protected $primaryKey = 'ProductID';

    public $timestamps = false;

    protected $fillable = [
        'ProductName',
        'SupplierID',
        'CategoryID',
        'QuantityPerUnit',
        'UnitPrice',
        'UnitsInStock',
        'UnitsOnOrder',
        'ReorderLevel',
        'Discontinued',
    ];

    protected function casts(): array
    {
        return [
            'UnitPrice' => 'decimal:2',
            'Discontinued' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'ProductID';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'SupplierID', 'SupplierID');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'ProductID', 'ProductID');
    }
}
