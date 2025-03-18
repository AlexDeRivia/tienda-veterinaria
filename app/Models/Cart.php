<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
