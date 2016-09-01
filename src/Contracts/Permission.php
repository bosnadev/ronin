<?php

namespace Bosnadev\Ronin\Contracts;

interface Permission
{
    /**
     * A Permission can be assigned to multiple Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * A permission can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();
}