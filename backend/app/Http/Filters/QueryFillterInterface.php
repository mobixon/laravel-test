<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

interface QueryFillterInterface
{
    public function apply(Builder $builder): Builder;
}
