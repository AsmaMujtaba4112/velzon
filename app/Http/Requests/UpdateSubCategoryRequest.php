<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends FormRequest {

    public function authorize(){
        return auth()->check();
    }

    public function rules(){
        $id = $this->route('sub_category') ? $this->route('sub_category')->id : null;
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required','string','max:255', Rule::unique('sub_categories','slug')->ignore($id)],
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'nullable|boolean'
        ];
    }
}
