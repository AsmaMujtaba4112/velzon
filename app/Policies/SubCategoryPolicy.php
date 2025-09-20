<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubCategory;

class SubCategoryPolicy
{
    /**
     * Determine whether the user can view any sub-categories.
     */
    public function viewAny(User $user): bool
    {
        return true; // sab users apni sub-categories dekh sakte hain
    }

    /**
     * Determine whether the user can view the sub-category.
     */
    public function view(User $user, SubCategory $subCategory): bool
    {
        return $user->id === $subCategory->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create sub-categories.
     */
    public function create(User $user): bool
    {
        return true; // har logged-in user create kar sakta hai
    }

    /**
     * Determine whether the user can update the sub-category.
     */
    public function update(User $user, SubCategory $subCategory): bool
    {
        return $user->id === $subCategory->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the sub-category.
     */
    public function delete(User $user, SubCategory $subCategory): bool
    {
        return $user->id === $subCategory->user_id || $user->is_admin;
    }
}
