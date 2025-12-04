<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;
    protected $table = 'postal_codes';
    protected $fillable = [
        'code'
    ];

    public function villages()
    {
        return $this->belongsToMany(
            Village::class, 
            'village_postal_codes', 
            'postal_code_id',       // Foreign key di pivot (dari postal_codes)
            'village_code',         // Related key di pivot (ke villages)
            'id',                   // ← TAMBAHKAN: Local key di postal_codes
            'village_code'          // ← TAMBAHKAN: Related key di villages
        );
    }

    public function districts()
    {
        return $this->belongsToMany(
            District::class, 
            'district_postal_codes', 
            'postal_code_id',       
            'district_code',
            'id',                   
            'district_code'         
        );
    }
}
