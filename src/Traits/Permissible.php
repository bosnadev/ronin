<?php

namespace Bosnadev\Ronin\Traits;

trait Permissible
{
    /**
     * Grant the provided Permissions to a Role
     *
     * @param array $permission
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function givePermission(...$permission)
    {
        return $this->permissions()->save($permission);
    }
}