<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;

trait GeneralTrait
{
    public function scopeInclude(Builder $query)
    {
        if (empty(request('included'))) {
            return $query;
        }

        $included = collect($this->canInclude);
        $relations = explode(',', request('included'));
        foreach ($relations as $key => $relation) {
            if (!$included->contains($relation)) {
                unset($relations[$key]);
            }
        }
        return $query->with($relations);
    }
    
    public function scopeFilter(Builder $query)
    {
        if (empty(request('filter'))) {
            return $query;
        }

        $included = collect($this->canFilter);
        $filters = request('filter');
        foreach ($filters as $key => $filter) {
            if (!$included->contains($key)) {
                unset($filters[$key]);
            } else {
                $query->orWhere($key, $filter);
            }
        }
        return $query;
    }

    public function scopeOrder(Builder $query)
    {
        $order = 'id';
        $sort = 'ASC';
        if (!empty(request('order'))) {
            $order = request('order');
        }
        if (!empty(request('sort')) && strtoupper(request('sort')) != $sort) {
            $sort = 'DESC';
        }

        $orderable = collect($this->canOrder);
        if (!$orderable->contains($order)) {
            return $query;
        }
        $query->orderBy($order, $sort);
    }

    public function scopeTrash(Builder $query)
    {
        if (empty(request('trash'))) {
            return $query;
        }
        if (request('trash') === 'only') {
            return $query->onlyTrashed();
        }
        if (request('trash')) {
            $query->withTrashed();
        }
        return $query;
    }
    public function scopeGetOrPaginate(Builder $query, $perPage=null)
    {
        if (!$perPage) {
            return $query->get();
        }

        return $query->paginate($perPage);

    }
}
