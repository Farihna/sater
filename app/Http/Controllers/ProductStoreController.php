<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Product as products;
use App\Models\Category;


class ProductStoreController extends Controller
{
    public function index()
    {
        $products = products::with(['category', 'detailSapi', 'detailPakan'])->get();
        return view('landing.index', compact('products'));
    }
}
