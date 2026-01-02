<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = ['user_id', 'store_name', 'store_description', 'status'];

    // Relationships

    // Merchant belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Merchant has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Merchant has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
