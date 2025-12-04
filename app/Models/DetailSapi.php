<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSapi extends Model
{
    use HasFactory;

    protected $table = 'detail_sapi';

    protected $fillable = [
        'product_id',
        'berat',
        'usia',
        'gender',
        'sertifikat_kesehatan'
    ];

    public function products(){
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
