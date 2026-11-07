<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLocation extends Model
{
    protected $table = 'orders_locations';

    protected $fillable = [
        'product_id',
        'customer_name',
        'quantity',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
