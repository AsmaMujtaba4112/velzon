<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller {
    public function __construct(){ $this->middleware('auth'); }

    public function index(Request $request){
        $user = $request->user();
        $q = SubCategory::with('category')->orderBy('id','desc');
        if(!$user->is_admin) $q->where('user_id', $user->id);
        return response()->json($q->get());
    }

    public function store(StoreSubCategoryRequest $request){
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug(SubCategory::class, Str::slug($data['slug'] ?? $data['name']));
        $sub = SubCategory::create($data);
        return response()->json(['message'=>'Sub-Category created','sub_category'=>$sub],201);
    }

    public function show(SubCategory $subCategory){
        $this->authorize('view', $subCategory);
        return response()->json($subCategory);
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory){
        $this->authorize('update', $subCategory);
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug(SubCategory::class, Str::slug($data['slug']), $subCategory->id);
        $subCategory->update($data);
        return response()->json(['message'=>'Sub-Category updated','sub_category'=>$subCategory]);
    }

    public function destroy(SubCategory $subCategory){
        $this->authorize('delete', $subCategory);
        $subCategory->delete();
        return response()->json(['message'=>'Sub-Category deleted']);
    }

    public function forCategory(Category $category, Request $request){
        $user = $request->user();
        $q = $category->subCategories();
        if(!$user->is_admin) $q->where('user_id', $user->id);
        return response()->json($q->where('is_active',1)->get());
    }

    protected function uniqueSlug($model, $slug, $ignoreId = null){
        $original = $slug; $i = 1;
        while($model::where('slug', $slug)->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))->exists()){
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
