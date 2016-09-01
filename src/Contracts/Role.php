<?php

namespace Bosnadev\Ronin\Contracts;

use Bosnadev\Ronin\Models\Permission;

interface Role
{
    /**
     * A Role can have multiple permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * A role can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

    /**
     * Grant the provided Permissions to a Role
     *
     * @param Permission $permission
     * @return Model
     */
    public function grantPermissionTo(Permission $permission);
}
