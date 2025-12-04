<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Vtiful\Kernel\Format;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []); 

        $provinces = Province::all();
        $cities = City::all();
        $districts = District::all();
        $villages = Village::all();
    
        $initialSubtotal = 0;
        foreach ($cartItems as $item) {
            $initialSubtotal += $item['price'] * $item['quantity'];
        }
        $shippingCost = 0; 
        $taxAmount = 0;    
        $initialGrandTotal = $initialSubtotal + $shippingCost + $taxAmount;
        return view('landing.checkout', [
            'cartItems' => $cartItems,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'shippingCost' => $shippingCost,       
            'taxAmount' => $taxAmount,             
            'totalPrice' => $initialSubtotal,      
            'initialGrandTotal' => $initialGrandTotal
        ]);
    }

    public function add(Request $request, Products $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:'. $product->stok,
        ]);
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);
        $cartKey = $product->id;
        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;

            if ($newQuantity > $product->stok) {
                return response()->json([
                    'message' => 'Gagal: Stok hanya tersedia (' . $product->stok . ').'
                ], 400); 
            }
            $cart[$cartKey]['quantity'] = $newQuantity;

        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->nama,
                'price' => $product->harga,
                'quantity' => $quantity,
                'image_url' => $product->image_url,
                'stok' => $product->stok 
            ];
        }
        session()->put('cart', $cart);

        return response()->json([
            'message' => '<strong>' . ucfirst($product->nama) . '</strong> ditambahkan ke keranjang.',
            'cart_count' => count($cart)
        ]);
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1', 
        ]);
        $newQuantity = (int) $request->input('quantity');
        $cart = Session::get('cart', []);
        if (!isset($cart[$itemId])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.'
            ], 404);
        }

        $product = Products::find($itemId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak valid.'
            ], 404);
        }
        
        if ($newQuantity > $product->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Kuantitas melebihi stok yang tersedia (' . $product->stok . ').'
            ], 400); 
        }

        $cart[$itemId]['quantity'] = $newQuantity;
        $cart[$itemId]['total_price'] = $newQuantity * $cart[$itemId]['price'];
        Session::put('cart', $cart);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui.',
            'item_total' => $cart[$itemId]['total_price'], 
            'subtotal' => $subtotal, 
            'new_quantity' => $newQuantity,
        ]);
    }

    public function remove($itemId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart_count' => count($cart) 
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.',
        ], 404);
    }
}
