<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time();
            // Simpan gambar ke direktori storage
            $image->storeAs('public/category', $imageName);
            $validated['image'] = $imageName;
        }

        $data = new Category();
        $data->name = $request->name;
        $data->image = $imageName; // Simpan nama file gambar ke database
        $data->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if (Storage::exists('public/category/' . $category->image)) {
                Storage::delete('public/category/' . $category->image);
            }
            
            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/category', $imageName);
            $validated['image'] = $imageName;
        }
    
        // Update category data
        $category->update($validated);
    
        return redirect()->route('category.index')->with('warning', 'Category updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (Storage::disk('public')->exists('category/' . $category->image)) {
            Storage::disk('public')->delete('category/' . $category->image);
        }
        $category->delete();
        return redirect()->route('category.index')->with('danger', 'Category deleted successfully');
    }
}
