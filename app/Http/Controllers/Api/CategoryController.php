<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function __construct(){ $this->middleware('auth'); }

    public function index(Request $request){
        $user = $request->user();
        $query = Category::withCount('subCategories','products')->orderBy('id','desc');
        if(!$user->is_admin) $query->where('user_id', $user->id);
        return response()->json($query->get());
    }

    public function store(StoreCategoryRequest $request){
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug(Category::class, Str::slug($data['slug'] ?? $data['name']));
        $category = Category::create($data);
        return response()->json(['message'=>'Category created','category'=>$category],201);
    }

    public function show(Category $category){
        $this->authorize('view', $category);
        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category){
        $this->authorize('update', $category);
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug(Category::class, Str::slug($data['slug']), $category->id);
        $category->update($data);
        return response()->json(['message'=>'Category updated','category'=>$category]);
    }

    public function destroy(Category $category){
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json(['message'=>'Category deleted']);
    }

    public function toggleStatus(Request $request, Category $category){
        $this->authorize('update', $category);
        $category->is_active = $request->boolean('is_active');
        $category->save();
        return response()->json(['message'=>'Status updated','is_active'=>$category->is_active]);
    }

    protected function uniqueSlug($model, $slug, $ignoreId = null){
        $original = $slug;
        $i = 1;
        while($model::where('slug', $slug)->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))->exists()){
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
