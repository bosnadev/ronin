<?php

namespace Bosnadev\Ronin\Models;

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

    public static function findBySlug($slug)
    {
        return static::where('slug', strtolower($slug))->first();
    }
}
