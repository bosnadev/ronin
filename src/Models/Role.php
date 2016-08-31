<?php

namespace Bosnadev\Ronin\Models;

use Bosnadev\Tests\Ronin\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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

    public function users()
    {
        return $this->belongsToMany(app(config('auth.model')) ?: app(config('auth.providers.users.model')))->withTimestamps();
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