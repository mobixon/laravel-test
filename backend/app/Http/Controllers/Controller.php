<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function sort(Request $request, Builder $query): Builder
    {
        if ($request->has('sort')) {
            $sort = $request->sort;
            $direction = $sort[0] === '-' ? 'desc' : 'asc';
            $sort = ltrim($sort, '-+');
            $query->orderBy($sort, $direction);
        }
        return $query;
    }
}
