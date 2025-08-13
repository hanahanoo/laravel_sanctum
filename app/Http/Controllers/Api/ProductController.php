<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Category;
use Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();
        $res   = [
            'success' => true,
            'data'    => $products,
            'message' => 'List products',
        ];

        return response()->json($res, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225|unique:products',
            'desc' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'id_category' => 'required|exists:categories,id',
        ]);


        $product = new Product();
        $gambarPath = null;
        if ($request->hasFile('foto')) {
            $gambarPath = $request->file('foto')->store('products', 'public');
        }

        
        $product->name        = $request->name;
        $product->desc        = $request->desc;
        $product->price       = $request->price;
        $product->stock       = $request->stock;
        $product->foto        = $gambarPath;
        $product->id_category = $request->id_category;
        $product->save();

        $res = [
            'success' => true,
            'data'    => $product,
            'message' => 'Store product'
        ];

        return response()->json($res, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Show Product Detail',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:225|unique:products',
            'desc' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'id_category' => 'required|exists:categories,id',
        ]);


        $product = Product::find($id);
        $gambarPath = null;
        if ($request->hasFile('foto')) {
            $gambarPath = $request->file('foto')->store('products', 'public');
        }

        
        $product->name        = $request->name;
        $product->desc        = $request->desc;
        $product->price       = $request->price;
        $product->stock       = $request->stock;
        $product->foto        = $gambarPath;
        $product->id_category = $request->id_category;
        $product->save();

        $res = [
            'success' => true,
            'data'    => $product,
            'message' => 'Update product'
        ];

        return response()->json($res, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        // delete image di storage
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
