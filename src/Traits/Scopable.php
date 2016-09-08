<?php

namespace Bosnadev\Ronin\Traits;

use Bosnadev\Ronin\Contracts\Scope;

trait Scopable
{
    /**
     * @return mixed
     */
    public function scopes()
    {
        return $this->belongsToMany(app(Scope::class))->withTimestamps()->withPivot('granted');
    }

    /**
     * Grant the provided Scope to a Role
     *
     * @param array $scopes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addScope(...$scopes)
    {
        $scopes = collect($scopes)
            ->filter(function ($scope) {
                if((bool) $this->scopeExists($scope)) {
                    return $scope;
                }
            })
            ->map(function ($scope) {
                return $this->scopeExists($scope);
        })->all();

        $this->scopes()->saveMany($scopes);
    }

    public function inScope($scope)
    {
        if(is_array($scope))
            return false;

        if(is_string($scope)) {
            // Get scope if exists, if not throw an exception
            $scope = $this->scopeExists($scope);
        }

        return (
            ($this->hasValidDirectScope($scope) || $this->hasRoleScope($scope))
            && ! $this->hasInvalidDirectScope($scope)
        );
    }

    /**
     * Test if a role has given scope
     *
     * @param Scope $scope
     * @return bool
     */
    protected function hasRoleScope(Scope $scope)
    {
        $user = app(config('ronin.users.model'));

        if($this instanceof $user) {
            return $this->hasRole($scope->roles);
        }

        return false;
    }

    protected function hasValidDirectScope(Scope $scope)
    {
        return $this->scopes->contains('slug', $scope->slug) && $this->scopes->contains('pivot.granted', 1);
    }

    protected function hasInvalidDirectScope(Scope $scope)
    {
    }

    protected function scopeExists($scope)
    {
        return app(Scope::class)->findBySlug($scope);
    }
}