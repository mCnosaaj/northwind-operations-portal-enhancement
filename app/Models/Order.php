<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'ORDERS';

    protected $primaryKey = 'OrderID';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'OrderDate' => 'datetime',
            'RequiredDate' => 'datetime',
            'ShippedDate' => 'datetime',
            'Freight' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'OrderID', 'OrderID');
    }
}
