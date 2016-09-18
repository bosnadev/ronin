<?php

namespace Connectum\Ronin\Models;

use Illuminate\Database\Eloquent\Model;
use Connectum\Ronin\Exceptions\ScopeNotFound;
use Connectum\Ronin\Contracts\Scope as ScopeContract;

class Scope extends Model implements ScopeContract
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
        $this->table = config('ronin.scopes.table');
    }

    /**
     * A Scope can be assigned to multiple Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * A Scope can be granted to may users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(app(config('ronin.users.model')))->withTimestamps();
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

    /**
     * Generic findBy method
     *
     * @param $attribute
     * @param $value
     * @return mixed
     */
    protected static function findBy($attribute, $value)
    {
        $scope = static::where($attribute, $value)->first();

        if(! $scope)
            throw new ScopeNotFound;

        return $scope;
    }
}
