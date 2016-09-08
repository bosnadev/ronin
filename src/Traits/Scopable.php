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
        return $this->belongsToMany(app(Scope::class))->withTimestamps();
    }

    /**
     * Grant the provided Scope to a Role
     *
     * @param array $scope
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addScope($scope)
    {
        $scope = collect($scope)
            ->filter(function ($scope) {
                if((bool) $this->scopeExists($scope)) {
                    return $scope;
                }
            })
            ->map(function ($scope) {
                return $this->scopeExists($scope);
        })->first();

        $this->scopes()->save($scope);
    }

    /**
     * Check if the role or the user has the given scope
     *
     * @param $scope
     * @return bool
     */
    public function inScope($scope)
    {
        if(is_array($scope))
            return false;

        if(is_string($scope)) {
            // Get scope if exists, if not throw an exception
            $scope = $this->scopeExists($scope);
        }

        return ($this->hasUserScope($scope) || $this->hasRoleScope($scope));
    }

    /**
     * Check if the role has a given scope
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

    /**
     * If the user has a given scope, it'll override the users role scope
     *
     * @param Scope $scope
     * @return bool
     */
    protected function hasUserScope(Scope $scope)
    {
        return $this->scopes->contains('slug', $scope->slug);
    }

    /**
     * Check if a given scope exists
     *
     * @param $scope
     * @return mixed
     */
    protected function scopeExists($scope)
    {
        return app(Scope::class)->findBySlug($scope);
    }
}
