<?php

namespace Bosnadev\Ronin\Models;

use Bosnadev\Ronin\Exceptions\PermissionNotFound;
use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Contracts\Permission as PermissionContract;

class Permission extends Model implements PermissionContract
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('ronin.permissions.table');
    }

    /**
     * A Permission can be assigned to multiple Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * A permission can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(app(config('ronin.users.model')) ?: app(config('auth.providers.users.model')))->withTimestamps();
    }

    public static function findById($id)
    {
        return static::findBy('id', $id);
    }

    public static function findByName($name)
    {
        return static::findBy('name', $name);
    }

    public static function findBySlug($slug)
    {
        return static::findBy('slug', strtolower($slug));
    }

    protected static function findBy($attribute, $value)
    {
        $permission = static::where($attribute, $value)->first();

        if(! $permission)
            throw new PermissionNotFound;

        return $permission;
    }
}
