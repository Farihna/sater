<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';

    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'address',
        'nik',
        'identity_document',
        'bank_name',
        'account_number',
        'account_holder_name',
        'status',
        'rejection_reason',
        'npwp',
        'joined_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
