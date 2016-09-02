<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Models\Role;

/**
 * Class RolableTrait
 * @package Bosnadev\Ronin\Traits
 */
trait RolableTrait
{
    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(app(Role::class))->withTimestamps();
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
     * @param null $roleId
     * @return bool
     */
    public function assignRole($roleId = null)
    {
        // Check if user already has this role
        if(! $this->roles->contains($roleId) && !is_null($roleId)) {
            return $this->roles()->attach($roleId);
        }

        return false;
    }

    /**
     * Check if the user has the given role
     *
     * @param string|Role $role
     * @return bool
     */
    public function hasRole($role)
    {
        if($role instanceof Role)
            return  $this->roles->contains('slug', $role->slug);

        if(is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return false;
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array $roles
     *
     * @return bool
     */
    public function hasAnyRole(array $roles)
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
}
