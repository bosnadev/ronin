<?php

namespace Connectum\Ronin\Models;

use Connectum\Ronin\Traits\Scopable;
use Illuminate\Database\Eloquent\Model;
use Connectum\Ronin\Contracts\Role as RoleContract;
use Connectum\Ronin\Contracts\Scope as ScopeContract;
use Connectum\Ronin\Exceptions\RoleNotFoundException;

class Role extends Model implements RoleContract
{
    use Scopable;

    protected $fillable = [
        'name', 'slug', 'description'
    ];

    public static $userModel;

    public static $scopeModel;

    /**
     * A Role can be assigned with scopes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes()
    {
        return $this->belongsToMany(app(ScopeContract::class))->withTimestamps();
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
     * Get Role slug
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get Role ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public static function findBySlug($slug)
    {
        $role = static::where('slug', $slug)->first();

        if(! $role) {
            throw new RoleNotFoundException();
        }

        return $role;
    }

    public function findById($id)
    {
        $role = static::where('id', $id)->first();

        if(! $role) {
            throw new RoleNotFoundException();
        }

        return $role;
    }
}
