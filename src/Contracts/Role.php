<?php

namespace Bosnadev\Ronin\Contracts;

interface Role
{
    /**
     * Multiple scopes can be added to a Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes();

    /**
     * A role can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();
}
