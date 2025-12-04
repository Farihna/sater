<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    public function showDetails(Products $product)
    {
        return view('landing.products.details', compact('product'));
    }
}
