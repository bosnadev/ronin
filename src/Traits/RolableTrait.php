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
     * Check if the user has at least one of the provided roles
     *
     * @param string|array|Role $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if($roles instanceof Role)
            return $this->userRoleSlug($roles->slug);

        if(is_string($roles)) {
            return $this->userRoleSlug($roles);
        }

        if(is_array($roles)) {
            foreach ($roles as $role) {
                if($this->userRoleSlug($role))
                    return true;
            }

            return false;
        }
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