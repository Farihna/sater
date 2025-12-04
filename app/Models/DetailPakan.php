<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPakan extends Model
{
    use HasFactory;

    protected $table = 'detail_pakan';

    protected $fillable = [
        'berat',
        'jenis_pakan',
    ];

    public function products(){
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
