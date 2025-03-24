<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Task::create([
            'name' => $request->name,
            'status' => 0,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tasks.index');
    }

    public function complete(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->status = 1;
        $task->save();

        return redirect()->route('tasks.index');
    }
}
