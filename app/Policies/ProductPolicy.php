<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return true; // sab users apne products list kar sakte hain
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->id === $product->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return true; // har logged-in user create kar sakta hai
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id || $user->is_admin;
    }
}
