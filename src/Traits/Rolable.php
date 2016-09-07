<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Contracts\Role as RoleContract;
use Bosnadev\Ronin\Exceptions\NoRoleProvidedException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Rolable
 * @package Bosnadev\Ronin\Traits
 */
trait Rolable
{
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
     * @param array|int|string|\Bosnadev\Ronin\Contracts\Role $roles
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

    public function revokeRole($role)
    {
        $this->roles()->detach($this->getRoleIfExists($role));
    }

    /**
     * Check if the user has the given role
     *
     * @param string||\Bosnadev\Ronin\Contracts\Role $roles
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
     * Check if user have role with a given slug
     *
     * @param $slug
     * @return mixed
     */
    public function userRoleSlug($slug)
    {
        return $this->roles->contains('slug', strtolower($slug));
    }

    public function getRoleIfExists($role)
    {
        if(is_string($role)) {
            return app(RoleContract::class)->findBySlug($role);
        }

        if(is_int($role)) {
            return app(RoleContract::class)->findById($role);
        }

        return $role;
    }
}
