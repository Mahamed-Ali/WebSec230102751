@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Tasks</h1>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <input type="text" name="name" placeholder="New Task" required>
        <button type="submit">Add Task</button>
    </form>

    <ul>
        @foreach($tasks as $task)
            <li>
                {{ $task->name }} - {{ $task->status ? 'Completed' : 'Pending' }}

                @if(!$task->status)
                    <form method="POST" action="{{ route('tasks.complete', $task->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit">Mark as Completed</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
