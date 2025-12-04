<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    protected $table = 'villages';

    protected $fillable = [
        'village_code',
        'district_id',
        'name'
    ];

    public function postalCodes()
    {
        return $this->belongsToMany(
            PostalCode::class,           // Model yang di-relate
            'village_postal_codes',      // Nama pivot table
            'village_code',              // Foreign key di pivot (dari table villages)
            'postal_code_id',            // Related key di pivot (ke table postal_codes)
            'village_code',              // ← TAMBAHKAN INI: Local key di table villages
            'id'                         // ← TAMBAHKAN INI: Related key di table postal_codes
        );
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
