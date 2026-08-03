<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'street', 'number', 'complement',
        'neighborhood', 'city', 'state', 'zipcode', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = ["{$this->street}, {$this->number}"];
        if ($this->complement) $parts[] = $this->complement;
        $parts[] = "{$this->neighborhood} — {$this->city}/{$this->state}";
        $parts[] = "CEP {$this->zipcode}";
        return implode(', ', $parts);
    }
}
