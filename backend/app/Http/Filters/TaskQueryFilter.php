<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskQueryFilter implements QueryFillterInterface
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        if ($this->request->has('title')) {
            $builder->where('title', 'like', '%' . $this->request->title . '%');
        }

        if ($this->request->has('description')) {
            $builder->where('description', 'like', '%' . $this->request->description . '%');
        }

        if ($this->request->has('status')) {
            $builder->where('status', "=", $this->request->status);
        }

        if ($this->request->has('priority_from')) {
            $builder->where('priority', ">=", $this->request->priority_from);
        }

        if ($this->request->has('priority_to')) {
            $builder->where('priority', "<=", $this->request->priority_to);
        }

        return $builder;
    }
}
