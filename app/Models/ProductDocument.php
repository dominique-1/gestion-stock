<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'filename',
        'original_name',
        'mime_type',
        'file_size',
        'file_path',
        'document_type',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconClassAttribute()
    {
        return match($this->mime_type) {
            'application/pdf' => 'fas fa-file-pdf text-red-500',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel text-green-500',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fas fa-file-word text-blue-500',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fas fa-file-powerpoint text-orange-500',
            'image/jpeg', 'image/png', 'image/gif' => 'fas fa-file-image text-purple-500',
            default => 'fas fa-file text-gray-500'
        };
    }

    public function getDocumentTypeLabelAttribute()
    {
        return match($this->document_type) {
            'fiche_technique' => 'Fiche technique',
            'manuel' => 'Manuel d\'utilisation',
            'certificat' => 'Certificat',
            'autre' => 'Autre',
            default => 'Document'
        };
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
