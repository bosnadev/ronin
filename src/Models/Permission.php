<?php

namespace Bosnadev\Ronin\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * A Permission can be assigned to multiple Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {

    }
}