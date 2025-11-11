<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'category_id',
        'nama',
        'deskripsi',
        'harga',
        'image_url',
        'stok',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function detailSapi(){
        return $this->hasOne(DetailSapi::class);
    }

    public function detailPakan(){
        return $this->hasOne(DetailPakan::class);
    }
}   
