<?php
// app/Policies/AuditLogPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditLogPolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user) {
        return $user->hasRole('Administrador') || $user->can('view_audit_logs');
    }

    public function view(User $user) {
        return $user->hasRole('Administrador') || $user->can('view_audit_logs');
    }

    public function export(User $user) {
        return $user->hasRole('Administrador');
    }
}
