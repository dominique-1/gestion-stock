<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SmartAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'type',
        'level',
        'title',
        'message',
        'product_id',
        'data',
        'is_read',
        'read_at',
        'email_sent',
        'email_sent_at',
        'dismissed',
        'dismissed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
        'dismissed' => 'boolean',
        'read_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];

    protected $dates = [
        'read_at',
        'email_sent_at',
        'dismissed_at',
    ];

    // Relations
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeCritical($query)
    {
        return $query->where('level', 'critical');
    }

    public function scopeWarning($query)
    {
        return $query->where('level', 'warning');
    }

    public function scopeInfo($query)
    {
        return $query->where('level', 'info');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'stock_min' => 'fas fa-arrow-down',
            'overstock' => 'fas fa-arrow-up',
            'expiry' => 'fas fa-clock',
            'prediction_risk' => 'fas fa-brain',
            'movement_anomaly' => 'fas fa-chart-line',
            default => 'fas fa-bell',
        };
    }

    public function getColorAttribute()
    {
        return match($this->level) {
            'critical' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            default => 'gray',
        };
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function dismiss()
    {
        $this->update([
            'dismissed' => true,
            'dismissed_at' => now(),
        ]);
    }

    public function markEmailSent()
    {
        $this->update([
            'email_sent' => true,
            'email_sent_at' => now(),
        ]);
    }

    public function getResolutionTime()
    {
        if (!$this->read_at) {
            return null;
        }
        
        return $this->created_at->diffInMinutes($this->read_at);
    }

    // Static methods
    public static function generateAlertId($type, $productId = null)
    {
        $prefix = match($type) {
            'stock_min' => 'SM',
            'overstock' => 'OS',
            'expiry' => 'EX',
            'prediction_risk' => 'PR',
            'movement_anomaly' => 'MA',
            default => 'AL',
        };
        
        $suffix = $productId ? "_{$productId}" : '';
        $timestamp = now()->format('YmdHis');
        
        return "{$prefix}{$suffix}_{$timestamp}";
    }

    public static function cleanupOldAlerts($days = 30)
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
