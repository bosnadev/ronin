<?php

namespace Bosnadev\Ronin\Contracts;

interface Scope
{
    /**
     * A Scope can be assigned to multiple Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * A scope can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();
}
