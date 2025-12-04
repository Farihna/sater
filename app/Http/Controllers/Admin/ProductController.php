<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['category', 'detailSapi', 'detailPakan'])->get();
        return view('admin.products.index', compact('products'));
    }

    public function create(){
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stok' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,pending',
        ]);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('products', 'public');
            $validated['image_url'] = $path;
        }

        $prefixMap = [1 => 'SPI', 2 => 'PKN', 3 => 'OBT', 4 => 'KBN'];
        $prefix = $prefixMap[$validated['category_id']] ?? 'PRD';
        $sku = $prefix . '-' . date('Ym') . '-' . strtoupper(Str::random(6));

        $user_id['user_id'] = Auth::id(); // atau $request->user()->id

        $product = Products::create(['sku' => $sku] + $validated + $user_id);

        $categoryId = $request->category_id;

        if($categoryId == 1){
            $request->validate([
                'berat' => 'required|numeric|min:0',
                'usia' => 'nullable|string|max:255',
                'gender' => 'required|in:jantan,betina',
                'sertifikat_kesehatan' => 'nullable|string|max:255',
            ]);
            
            $product->detailSapi()->create([
                'berat' => $request->berat,
                'usia' => $request->usia,
                'gender' => $request->gender,
                'sertifikat_kesehatan' => $request->sertifikat_kesehatan,
            ]);
        }else if($categoryId == 2){
            $request->validate([
                'berat' => 'nullable|numeric|min:0',
                'jenis_pakan' => 'required|string|max:255',
            ]);

            $product->detailPakan()->create([
                'berat' =>$request->berat,
                'jenis_pakan' => $request->jenis_pakan,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Products $product)
    {   
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Products $product)
    {
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stok' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,pending',
        ]);

        if ($request->hasFile('image_url')) {
            if($product->image_url){
                Storage::disk('public')->delete($product->image_url);
            }
            $path = $request->file('image_url')->store('products', 'public');
            $validated['image_url'] = $path;
        }else{
            unset($validated['image_url']);
        }

        $product->update($validated);

        $categoryId = $request->category_id;

        if($categoryId == 1){
            $request->validate([
                'berat' => 'required|numeric|min:0',
                'usia' => 'nullable|string|max:255',
                'gender' => 'required|in:jantan,betina',
                'sertifikat_kesehatan' => 'nullable|string|max:255',
            ]);

            $product->detailSapi()->updateOrCreate(
            ['product_id' => $product->id],
            [
                    'berat' => $request->berat,
                    'usia' => $request->usia,
                    'gender' => $request->gender,
                    'sertifikat_kesehatan' => $request->sertifikat_kesehatan,
                ]
            );
        }elseif($categoryId == 2){
            $request->validate([
                'berat' => 'nullable|numeric|min:0',
                'jenis_pakan' => 'required|string|max:255',
            ]);

            $product->detailPakan()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'berat' =>$request->berat,
                    'jenis_pakan' => $request->jenis_pakan,
                ]
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Products $product)
    {
        if($product->image_url){
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function filter(Request $request){
        $category = $request->query('category');

        $categoryMap = [
            'sapi' => 1,
            'pakan' => 2,
            'obat' => 3,
            'kebutuhan' => 4,
        ];

        $categoryId = $categoryMap[$category] ?? null;

        if(!$categoryId){
            return response()->json(['error' => 'kategori tidak valid'], 400);
        }

        $query = Products::where('category_id', $categoryId)->with(['detailSapi', 'detailPakan'])->orderBy('id', 'desc');

        $products = $query->get();

        return response()->json(['products' => $products]);
    }
}