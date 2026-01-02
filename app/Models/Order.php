<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'merchant_id', 'total_amount', 'status'];

    // Relationships

    // Order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order belongs to a merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    // Order has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Through order items, order has many products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity', 'price');
    }
}
