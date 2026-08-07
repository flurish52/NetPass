<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage plans');
    }

    public function view(User $user, Plan $plan): bool
    {
        return $user->business_id === $plan->business_id && $user->can('manage plans');
    }

    public function create(User $user): bool
    {
        return $user->can('manage plans');
    }

    public function update(User $user, Plan $plan): bool
    {
        return $user->business_id === $plan->business_id && $user->can('manage plans');
    }

    public function delete(User $user, Plan $plan): bool
    {
        return $user->business_id === $plan->business_id && $user->can('manage plans');
    }
}
