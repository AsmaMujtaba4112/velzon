<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest {

    public function authorize(){
        return auth()->check();
    }

    public function rules(){
        $categoryId = $this->route('category') ? $this->route('category')->id : null;
        return [
            'name'=>'required|string|max:255',
            'slug'=>['required','string','max:255', Rule::unique('categories','slug')->ignore($categoryId)],
            'is_active'=>'nullable|boolean'
        ];
    }
}
