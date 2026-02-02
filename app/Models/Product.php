<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'barcode',
        'supplier',
        'price',
        'category_id',
        'stock_min',
        'stock_optimal',
        'current_stock',
        'expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function documents()
    {
        return $this->hasMany(ProductDocument::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function inventoryLines()
    {
        return $this->hasMany(InventoryLine::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= stock_min');
    }

    public function scopeOverstock($query)
    {
        return $query->whereRaw('current_stock >= stock_optimal');
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays($days));
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->stock_min;
    }

    public function isOverstock(): bool
    {
        return $this->current_stock >= $this->stock_optimal;
    }

    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expires_at && $this->expires_at->lessThanOrEqualTo(now()->addDays($days));
    }

    public function stockValue(): float
    {
        return $this->price * $this->current_stock;
    }
}
