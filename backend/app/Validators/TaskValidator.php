<?php

namespace App\Validators;

use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidatorResult;

class TaskValidator
{
    /**
     * @param array $data
     *
     * @return \Illuminate\Validation\Validator
     */
    public static function create(array $data): ValidatorResult
    {
        return Validator::make($data, [
            'status' => 'prohibited',
            'title' => 'required|max:255',
            'description' => 'required|nullable',
            'priority' => 'required|integer|between:1,5',
            'parent_id' => 'nullable|exists:tasks,id',
        ]);
    }

    public static function update(array $data, Task $task): ValidatorResult
    {
        return Validator::make($data, [
            'status' => 'prohibited',
            'title' => 'required|max:255',
            'description' => 'required|nullable',
            'priority' => 'required|integer|between:1,5',
            'parent_id' => [
                'nullable',
                Rule::exists('tasks', 'id')->where(function ($query) use ($task) {
                    return $query->where('id', '<>', $task->getId());
                })
            ],
        ]);
    }
}
