<?php

use App\Models\User;

// app/Policies/FacultyPolicy.php
class FacultyPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('view faculties');
    }

    public function create(User $user)
    {
        return $user->can('manage faculties');
    }
}
