<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    public function __construct(){ $this->middleware('auth'); }

    public function index(Request $request){
        $user = $request->user();
        $q = Product::with(['category','subCategory'])->orderBy('id','desc');
        if(!$user->is_admin) $q->where('user_id', $user->id);
        return response()->json($q->get());
    }

    public function store(StoreProductRequest $request){
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $paths = [];
        if($request->hasFile('images')){
            foreach($request->file('images') as $file) $paths[] = $file->store('products','public');
        }
        $data['images'] = $paths;
        $product = Product::create($data);
        return response()->json(['message'=>'Product created','product'=>$product],201);
    }

    public function show(Product $product){
        $this->authorize('view', $product);
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $this->authorize('update', $product);
        $data = $request->validated();
        $paths = $product->images ?? [];
        if($request->hasFile('images')){
            foreach($request->file('images') as $file) $paths[] = $file->store('products','public');
        }
        $data['images'] = $paths;
        $product->update($data);
        return response()->json(['message'=>'Product updated','product'=>$product]);
    }

    public function destroy(Product $product){
        $this->authorize('delete', $product);
        // delete images from storage
        foreach($product->images ?? [] as $path) { Storage::disk('public')->delete($path); }
        $product->delete();
        return response()->json(['message'=>'Product deleted']);
    }

    public function toggleStatus(Request $request, Product $product){
        $this->authorize('update', $product);
        $product->is_active = $request->boolean('is_active');
        $product->save();
        return response()->json(['message'=>'Status updated','is_active'=>$product->is_active]);
    }

    public function deleteImage(Request $request, Product $product){
        $this->authorize('update', $product);
        $path = $request->input('path');
        if(!$path) return response()->json(['message'=>'No path provided'],422);
        $images = $product->images ?? [];
        if(($key = array_search($path,$images)) !== false){
            Storage::disk('public')->delete($path);
            array_splice($images,$key,1);
            $product->images = $images;
            $product->save();
            return response()->json(['message'=>'Image deleted']);
        }
        return response()->json(['message'=>'Image not found'],404);
    }
}
