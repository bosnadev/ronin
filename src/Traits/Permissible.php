<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Models\Permission;
use Bosnadev\Ronin\Contracts\Permission as PermissionContract;

trait Permissible
{
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

    public function can($permission)
    {
        if(is_string($permission)) {
            $permission = $this->permissionExists($permission);

            if(! $permission)
                return false;
        }

        return $this->permissions->contains($permission);
    }

    protected function permissionExists($permission)
    {
        return app(PermissionContract::class)->findBySlug($permission);
    }
}