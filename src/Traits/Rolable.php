<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Contracts\Role as RoleContract;
use Bosnadev\Ronin\Exceptions\NoRoleProvidedException;
use Illuminate\Support\Collection;

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
     * @param int|string|\Bosnadev\Ronin\Contracts\Role $role
     * @return bool
     */
    public function assignRole($role = null)
    {
        if(is_null($role))
            throw new NoRoleProvidedException('You need to provide a role identifier to assign a new role.');

        // Check if user already has this role
        if(! $this->roles->contains($role)) {
            return $this->roles()->attach($role);
        }

        return false;
    }

    /**
     * Check if the user has the given role
     *
     * @param string|array|\Bosnadev\Ronin\Contracts\Role $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if($roles instanceof RoleContract)
            return  $this->roles->contains('id', $roles->id);

        // We can check role existence by it's slug
        if(is_string($roles))
            return $this->roles->contains('slug', $roles);

        // We can check role existence by it's ID
        if(is_int($roles))
            return  $this->roles->contains('id', $roles);

        return (bool) $roles->intersect($this->getRoles())->count();
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
}
