<?php
namespace App\Traits;

use App\Models\Scope;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait StoreTrait
{
    public function scopeHasScope(Builder $query, $name)
    {
        if(!auth()->user()){
            return false;
        }
        $scope= $this->scope()->where('name', $name)->first();
        
        return $scope?true:false;
    }
    public function scopeAssignScopes(Builder $query, Array $scopes)
    {
        $keys=array_keys($scopes);
        $scopes=array_column($scopes, 'name')?:$scopes;
        $allScopes=Scope::get()->pluck('name');
        foreach ($scopes as $key => $scope) {
            if (!$allScopes->contains($scope)) {
                unset($scopes[$key]);
            }
        }
        $store=$this->getModel();
        $store->scope()->attach($scopes);
    }
}

