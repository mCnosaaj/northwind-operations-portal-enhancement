<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'SUPPLIERS';

    protected $primaryKey = 'SupplierID';

    public $timestamps = false;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'SupplierID', 'SupplierID');
    }
}
