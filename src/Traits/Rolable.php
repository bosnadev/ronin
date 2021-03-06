<?php

namespace Connectum\Ronin\Traits;

use Connectum\Ronin\Contracts\Role as RoleContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class Rolable
 * @package Connectum\Ronin\Traits
 */
trait Rolable
{
    use Scopable;

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(app(RoleContract::class))->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Assign the given role to the user
     *
     * @param array|int|string|\Connectum\Ronin\Contracts\Role $roles
     * @return RoleContract|Rolable
     */
    public function assignRole(...$roles)
    {
        $roles = collect($roles)
            ->flatten()
            ->map(function ($role) {
                return $this->getRoleIfExists($role );
            })->all();

        $this->roles()->saveMany($roles);

        return $this;
    }

    /**
     * Synchronise user roles with a new ones
     *
     * @param array|string|int|\Connectum\Ronin\Contracts\Role ...$roles
     * @return RoleContract|Rolable
     */
    public function syncRoles(...$roles)
    {
        $this->roles()->detach();

        return $this->assignRole($roles);
    }

    /**
     * Revoke the given role from the user
     *
     * @param $role
     */
    public function revokeRole($role)
    {
        $this->roles()->detach($this->getRoleIfExists($role));
    }

    /**
     * Check if the user has the given role
     *
     * @param string||\Connectum\Ronin\Contracts\Role $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        foreach ($this->roles as $existingRole){
            if($roles instanceof RoleContract) {
                return $existingRole->getId() === $roles->getId();
            }

            if($roles instanceof Collection) {
                return (bool) $roles->intersect($this->getRoles())->count();
            }

            if($existingRole->getId() === $roles || $existingRole->getSlug() === $roles) {
                return true;
            }
        };

        return false;
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        $hasRole = collect($roles)->filter(function ($role) {
            if($this->hasRole($role))
                return $role;
        })->count();

        return (bool) $hasRole;
    }

    /**
     * Check if given role if exits. If given role is an instance of a \Connectum\Ronin\Contracts\Role
     * then we'll just return that instance
     *
     * @param $role
     * @return mixed
     */
    protected function getRoleIfExists($role)
    {
        if(is_string($role)) {
            return app(RoleContract::class)->findBySlug(Str::lower($role));
        }

        if(is_int($role)) {
            return app(RoleContract::class)->findById($role);
        }

        return $role;
    }
}
