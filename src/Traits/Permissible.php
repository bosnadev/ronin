<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Contracts\Permission as PermissionContract;

trait Permissible
{
    public function permissions()
    {
        return $this->belongsToMany(app(PermissionContract::class))->withTimestamps()->withPivot('granted');
    }
    /**
     * Grant the provided Permissions to a Role
     *
     * @param array $permissions
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function givePermission(...$permissions)
    {
        $permissions = collect($permissions)
            ->filter(function ($permission) {
                if((bool) $this->permissionExists($permission)) {
                    return $permission;
                }
            })
            ->map(function ($permission) {
                return $this->permissionExists($permission);
        })->all();

        $this->permissions()->saveMany($permissions);
    }

    public function hasPermission($permission)
    {
        if(is_array($permission))
            return false;

        if(is_string($permission)) {
            // Get permission if exists, if not throw an exception
            $permission = $this->permissionExists($permission);
        }

        return (
            ($this->hasValidDirectPermission($permission) || $this->hasRolePermission($permission))
            && ! $this->hasInvalidDirectPermission($permission)
        );
    }

    /**
     * Test if a role has given permission
     *
     * @param Permission $permission
     * @return
     */
    protected function hasRolePermission(PermissionContract $permission)
    {
        return $this->hasRole($permission->roles);
    }

    protected function hasValidDirectPermission(PermissionContract $permission)
    {
    }

    protected function hasInvalidDirectPermission(PermissionContract $permission)
    {
    }

    public function hasAllPermissions($permissions)
    {
    }

    protected function permissionExists($permission)
    {
        return app(PermissionContract::class)->findBySlug($permission);
    }
}