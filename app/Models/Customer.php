<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'CUSTOMERS';

    protected $primaryKey = 'CustomerID';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
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
    ];

    public function getRouteKeyName(): string
    {
        return 'CustomerID';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'CustomerID', 'CustomerID');
    }
}
