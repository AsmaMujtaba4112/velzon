<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'name',
        'quantity',
        'category_id',
        'sub_category_id',
        'images',
        'unit_price',
        'cost_price_per_unit',
        'is_active',
        'user_id'
    ];
    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean'
    ];
    public function category(){
         return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
