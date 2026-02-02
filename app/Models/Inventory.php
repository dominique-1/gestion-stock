<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'performed_at',
        'user_id',
        'note',
        'status',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(InventoryLine::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function close()
    {
        $this->status = 'closed';
        $this->save();

        foreach ($this->lines as $line) {
            $diff = $line->qty_diff;
            if ($diff !== 0) {
                $this->product->current_stock += $diff;
                $this->product->save();

                StockMovement::create([
                    'product_id' => $line->product_id,
                    'type' => $diff > 0 ? 'in' : 'out',
                    'reason' => 'correction',
                    'quantity' => abs($diff),
                    'moved_at' => now(),
                    'user_id' => $this->user_id,
                    'note' => 'Inventaire #' . $this->id . ' - ' . ($line->justification ?? 'Ajustement automatique'),
                ]);
            }
        }
    }
}
