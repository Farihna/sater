<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable =  [
        'user_id',
        'type',
        'is_default',
        'label',
        'recipient_name',
        'phone_number',
        'address_line',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'zip_code', 
        'province', 'city', 'district', 'village'
    ];
}
