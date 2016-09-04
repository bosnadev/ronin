<?php

namespace Bosnadev\Ronin\Models;

use Bosnadev\Ronin\Traits\Permissible;
use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Contracts\Role as RoleContract;
use Bosnadev\Ronin\Contracts\Permission as PermissionContract;

class Role extends Model implements RoleContract
{
    use Permissible;

    protected $fillable = [
        'name', 'slug', 'description'
    ];

    static $userModel;

    static $permissionModel;

    /**
     * A Role can have multiple permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(app(PermissionContract::class))->withTimestamps();
    }

    /**
     * A role can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(app(config('ronin.users.model')))->withTimestamps();
    }
}
