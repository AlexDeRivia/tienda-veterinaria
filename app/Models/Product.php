<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image_hash) {
            return route('catalogo.imagen', ['product' => $this->id, 'version' => $this->image_hash]);
        }

        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }

        $legacyPath = 'productos/'.$this->id.'.jpg';

        return file_exists(public_path($legacyPath))
            ? asset($legacyPath)
            : asset('productos/default.png');
    }
}
