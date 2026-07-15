<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'ORDER_DETAILS';

    public $incrementing = false;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'UnitPrice' => 'decimal:2',
            'Discount' => 'float',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
