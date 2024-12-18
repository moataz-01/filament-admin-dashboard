<?php

namespace App\Policies;

use App\Models\Admin;

class ExceptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_exception');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin): bool
    {
        return $admin->can('view_exception');
    }
}
