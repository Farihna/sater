<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_type',
        'amount',
        'transaction_status',
        'transaction_date',
        'transaction_reference'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
