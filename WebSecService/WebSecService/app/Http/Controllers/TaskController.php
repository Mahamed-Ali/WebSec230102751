<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Auth::user()->tasks;
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        Auth::user()->tasks()->create([
            'name' => $request->name
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => !$task->status
        ]);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->back();
    }
}
