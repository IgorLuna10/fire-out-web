<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'category', 'price', 'stock_qty'];

    protected $casts = ['price' => 'decimal:2'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_sku', 'sku');
    }

    public function getPriceFormattedAttribute(): string
    {
        if ($this->price === null) return 'Sob Consulta';
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function isLowStock(): bool
    {
        return $this->stock_qty <= 5;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_qty <= 0;
    }
}
