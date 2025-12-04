<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Partner;
use App\Models\Products;

class AdminController extends Controller
{
    public function admin()
    {
        $productCount = Products::query()->count();
        return view('admin.index', compact('productCount'));
    }

    public function products()
    {
        return view('admin.products');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function partners()
    {
        $partners = Partner::where('status', 'active')->with('user')->get();
        return view('admin.partners.index', compact('partners'));
    }
    public function partnerVerification()
    {
        $partners = Partner::with('user')->where('status', 'pending')->get();
        return view('admin.partnerVerif.index', compact('partners'));
    }   

}