<?php
namespace App\Traits;

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
}

