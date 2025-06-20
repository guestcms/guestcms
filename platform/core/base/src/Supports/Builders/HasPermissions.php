<?php

namespace Guestcms\Base\Supports\Builders;

use Guestcms\ACL\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
    /**
     * @var string[]
     */
    protected array $permissions = [];

    public function permission(string $permission): static
    {
        $this->permissions[] = $permission;

        return $this;
    }

    public function anyPermissions(array $permissions): static
    {
        $this->permissions = array_merge($this->permissions, $permissions);

        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function currentUserHasAnyPermissions(): bool
    {
        if (! Auth::guard()->user() instanceof User) {
            return true;
        }

        return empty($this->permissions) || collect($this->permissions)
                ->filter(
                    fn (string $permission) => Auth::guard()->user() instanceof User && Auth::guard()->user()->hasPermission($permission)
                )
                ->isNotEmpty();
    }
}
