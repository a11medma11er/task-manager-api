<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // جلب كل المهام
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tasks = $user->tasks()->paginate(10);
        return response()->json($tasks);
    }

    // إضافة مهمة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $task = $user->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
        ]);

        return response()->json($task, 201);
    }

    // عرض مهمة واحدة
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        return response()->json($task);
    }

    // تعديل مهمة
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'nullable',
            'status' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        if ($request->has('title')) {
            $task->title = $request->title;
        }
        if ($request->has('description')) {
            $task->description = $request->description;
        }
        if ($request->has('status')) {
            $task->status = $request->status;
        }
        if ($request->has('due_date')) {
            $task->due_date = $request->due_date;
        }
        
        $task->save();

        return response()->json($task);
    }

    // حذف مهمة
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id != auth()->id()) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted'], 200);
    }
}
