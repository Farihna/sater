<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $table = 'districts';
    protected $fillable = [
        'district_code',
        'city_id',
        'name'
    ];

    public function postalCodes()
    {
        return $this->belongsToMany(
            PostalCode::class, 
            'district_postal_codes',
            'district_code',         
            'postal_code_id'        
        );
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }
}
