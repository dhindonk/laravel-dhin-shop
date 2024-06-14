<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil input pencarian dari request
        $searchTerm = $request->input('name');
    
        // Query produk dengan kategori terkait
        $productsQuery = Product::with('category') // Asumsikan Anda memiliki relationship 'category' di model Product
            ->orderBy('id', 'DESC');
    
        // Terapkan pencarian jika ada
        if ($searchTerm) {
            $productsQuery->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        // Paginate hasilnya
        $products = $productsQuery->paginate(10);
    
        return view('pages.product.index', compact('products'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hapus titik dari input price
        $request->merge(['price' => str_replace('.', '', $request->price)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:9223372036854775807', // Validasi batas maksimum bigInteger
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = (int) $request->price; // Mengonversi ke integer
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/assets/products', $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Product created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('pages.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Hapus titik dari input price
        $request->merge(['price' => str_replace('.', '', $request->price)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:9223372036854775807', // Validasi batas maksimum bigInteger
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);
        $data['price'] = (int) $data['price']; // Mengonversi ke integer

        if ($request->hasFile('image')) {
            $imagePath = $product->image;
            if (Storage::disk('public')->exists('assets/products/' . $imagePath)) {
                Storage::disk('public')->delete('assets/products/' . $imagePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/assets/products', $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('product.index')->with('warning', 'Product successfully updated');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('danger', 'Product successfully deleted');
    }
}
