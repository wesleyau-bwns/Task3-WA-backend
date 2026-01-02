<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['merchant_id', 'name', 'description', 'price', 'status'];

    // Relationships

    // Product belongs to a merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    // Product can appear in many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Through order items, product belongs to many orders
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('quantity', 'price');
    }
}
