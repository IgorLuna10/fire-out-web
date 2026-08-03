<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'status',
        'customer_name', 'customer_email', 'customer_phone', 'company',
        'document_type', 'document_number',
        'ship_street', 'ship_number', 'ship_complement',
        'ship_neighborhood', 'ship_city', 'ship_state', 'ship_zipcode',
        'payment_method', 'installments',
        'subtotal', 'has_consulta', 'notes',
    ];

    protected $casts = [
        'subtotal'    => 'decimal:2',
        'has_consulta' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Pendente',
            'confirmed'  => 'Confirmado',
            'processing' => 'Em Processamento',
            'completed'  => 'Concluído',
            'cancelled'  => 'Cancelado',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#f59e0b',
            'confirmed'  => '#3b82f6',
            'processing' => '#8b5cf6',
            'completed'  => '#16a34a',
            'cancelled'  => '#ef4444',
            default      => '#6b7280',
        };
    }

    public function getTotalDisplayAttribute(): string
    {
        $sub = 'R$ ' . number_format($this->subtotal, 2, ',', '.');
        if ($this->has_consulta && $this->subtotal > 0) return $sub . ' + sob consulta';
        if ($this->has_consulta) return 'Sob Consulta';
        return $sub;
    }

    public function getShippingAddressAttribute(): ?string
    {
        if (!$this->ship_street) return null;
        $parts = ["{$this->ship_street}, {$this->ship_number}"];
        if ($this->ship_complement) $parts[] = $this->ship_complement;
        $parts[] = "{$this->ship_neighborhood} — {$this->ship_city}/{$this->ship_state}";
        $parts[] = "CEP {$this->ship_zipcode}";
        return implode(', ', $parts);
    }

    public static function generateNumber(): string
    {
        return 'FO-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
}
