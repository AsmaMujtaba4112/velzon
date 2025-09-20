<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest {

    public function authorize(){
        return auth()->check();
    }

    public function rules(){
        return [
            'name'=>'required|string|max:255',
            'slug'=>['required','string','max:255','unique:categories,slug'],
            'is_active'=>'nullable|boolean'
        ];
    }
}
