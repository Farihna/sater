<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'category_id',
        'user_id',
        'nama',
        'deskripsi',
        'harga',
        'image_url',
        'stok',
        'status',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function detailSapi(){
        return $this->hasOne(DetailSapi::class, 'product_id', 'id');
    }

    public function detailPakan(){
        return $this->hasOne(DetailPakan::class, 'product_id', 'id');
    }
}   
