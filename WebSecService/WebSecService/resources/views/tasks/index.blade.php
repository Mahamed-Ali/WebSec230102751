@extends('layouts.master')

@section('title', 'To-Do List')

@section('content')

<div class="container">
    <h2>To-Do List</h2>
    
    <!-- Task Creation Form -->
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <input type="text" name="name" required>
        <button type="submit">Add Task</button>
    </form>

    <ul>
        @foreach($tasks as $task)
            <li>
                {{ $task->name }} - Status: {{ $task->status ? 'Completed' : 'Pending' }}
                
                <!-- Toggle Status Form -->
                <form action="{{ route('tasks.update', $task) }}" method="POST" style="display: inline;">
                    @csrf @method('PATCH')
                    <button type="submit">Toggle Status</button>
                </form>
                
                <!-- Delete Task Form -->
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>


    
@endsection