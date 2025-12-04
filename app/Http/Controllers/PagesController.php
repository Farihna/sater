<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;

class PagesController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }
    public function products()
    {
        $products = Products::with(['category', 'detailSapi', 'detailPakan'])->get();
        return view('landing.products.index', compact('products'));
    }
    public function productDetails()
    {
        return view('landing.products.details');
    }
    public function partner()
    {
        $products = Products::with(['category', 'detailSapi', 'detailPakan'])->where('user_id', Auth::id())->get();
        return view('landing.partner.index', compact('products'));
    }
    public function login()
    {
        return view('landing.login');
    }
    public function register()
    {
        return view('landing.register');
    }
    public function becomePartner()
    {
        return view('landing.becomePartner');
    }
    public function checkout()
    {
        return view('landing.checkout');
    }
    public function profileSettings()
    {
        return view('landing.profileSettings');
    }
    public function myOrders()
    {
        return view('landing.myOrders');
    }
}