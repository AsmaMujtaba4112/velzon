<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubCategoryRequest extends FormRequest {

    public function authorize(){
        return auth()->check();
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sub_categories,slug',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'nullable|boolean'
        ];
    }
}
