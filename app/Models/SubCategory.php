<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model {

    protected $fillable = ['name','slug','user_id','category_id','is_active'];

    public function category(){
         return $this->belongsTo(Category::class);
    }

    public function user(){
         return $this->belongsTo(User::class);
    }
    public function products(){
         return $this->hasMany(Product::class);
    }
}
