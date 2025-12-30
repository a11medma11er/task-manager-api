<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Models\Task;

class TaskController extends Controller
{
    // جلب كل المهام
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tasks = $user->tasks()->latest()->paginate(10);
        
        return new TaskCollection($tasks);
    }

    // إضافة مهمة جديدة
    public function store(StoreTaskRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $task = $user->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
        ]);

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    // عرض مهمة واحدة
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authorized'
            ], 403);
        }

        return new TaskResource($task);
    }

    // تعديل مهمة
    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authorized'
            ], 403);
        }

        $task->update($request->validated());

        return new TaskResource($task);
    }

    // حذف مهمة
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
