<?php

namespace Bosnadev\Ronin\Models;

use Bosnadev\Ronin\Traits\Scopable;
use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Contracts\Role as RoleContract;
use Bosnadev\Ronin\Contracts\Scope as ScopeContract;

class Role extends Model implements RoleContract
{
    use Scopable;

    protected $fillable = [
        'name', 'slug', 'description'
    ];

    static $userModel;

    static $scopeModel;

    /**
     * A Role can be assigned with scopes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes()
    {
        return $this->belongsToMany(app(ScopeContract::class))->withTimestamps()->withPivot('granted');
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
