<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'transaction_id',
        'payment_status',
        'payment_date'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    // Relasi ke Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
