<?php

namespace App\Http\Controllers;

use App\Http\Filters\TaskQueryFilter;
use App\Models\Task;
use App\Validators\TaskValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filter = new TaskQueryFilter($request);
        $tasks = Task::filter($filter)->with('subtasks');
        $tasks = $this->sort($request, $tasks);

        return response()->json(
            $tasks->paginate($request->get('per_page', 10))
        );
    }

    /**
     * Store a newly created Task in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = TaskValidator::create($data);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = new Task();
        $task->fill($data);
        $task->save();

        return response()->json($task, 201);
    }

    /**
     * Display the specified Task.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::with('subtasks')->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified Task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        $validator = TaskValidator::create($data);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::findOrFail($id);

        $task->fill($data);
        $task->save();

        return response()->json($task);
    }

    /**
     * Remove the specified Task from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Task::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    /**
     * Move task to done status
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveToDone(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->status = Task::STATUS_DONE;
        $task->save();
        return response()->json($task);
    }
}
