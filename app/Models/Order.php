<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_amount',
        'order_status',
        'shipping_cost',
        'order_date',
        'shipping_fee',
        'discount',
        'payment_method',
        'shipping_address',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }
}
