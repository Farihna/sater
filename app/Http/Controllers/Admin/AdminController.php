<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminController extends Controller
{
    public function admin()
    {
        $productCount = Product::query()->count();
        return view('admin.index', compact('productCount'));
    }

    public function products()
    {
        return view('admin.products');
    }
    
}
