<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_sku', 'product_name', 'variant',
        'unit_price', 'qty', 'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_sku', 'sku');
    }

    public function getPriceDisplayAttribute(): string
    {
        return $this->unit_price !== null
            ? 'R$ ' . number_format($this->unit_price, 2, ',', '.')
            : 'Sob Consulta';
    }
}
