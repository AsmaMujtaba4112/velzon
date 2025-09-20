<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest {

    public function authorize(){
        return auth()->check();
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'images.*' => 'nullable|image|max:5120',
            'unit_price' => 'required|numeric|min:0',
            'cost_price_per_unit' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean'
        ];
    }
}
