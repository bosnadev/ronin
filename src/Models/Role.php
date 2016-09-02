<?php

namespace Bosnadev\Ronin\Models;

use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Contracts\Role as RoleContract;

class Role extends Model implements RoleContract
{
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
        return $this->belongsToMany(Permission::class);
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

    /**
     * Grant the provided Permissions to a Role
     *
     * @param Permission $permission
     * @return Model
     */
    public function grantPermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    public static function setUserModel($userModel)
    {
        static::$userModel = $userModel;
    }

    public static function getUserModel()
    {
        return static::$userModel;
    }

    public static function setPermissionModel($permissionModel)
    {
        static::$permissionModel = $permissionModel;
    }

    public static function getPermissionModel()
    {
        return static::$permissionModel;
    }
}
