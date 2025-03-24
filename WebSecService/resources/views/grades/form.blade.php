@extends('layouts.master')

@section('title', $grade->id ? 'Edit Grade' : 'Add Grade')

@section('content')

<h1>{{ $grade->id ? 'Edit' : 'Add' }} Grade</h1>

<form action="{{ $grade->id ? route('grades_save', $grade->id) : route('grades_save') }}" method="POST">
    @csrf

    @if($grade->id)
    <div class="mb-3">
        <label for="id" class="form-label">Grade ID</label>
        <input type="text" class="form-control" value="{{ $grade->id }}" disabled>
    </div>
    @endif

    <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" name="subject" class="form-control" value="{{ old('subject', $grade->subject) }}" required>
    </div>

    <div class="mb-3">
        <label for="term" class="form-label">Term</label>
        <input type="text" name="term" class="form-control" value="{{ old('term', $grade->term) }}" required>
    </div>

    <div class="mb-3">
        <label for="credit_hours" class="form-label">Credit Hours</label>
        <input type="number" name="credit_hours" class="form-control" value="{{ old('credit_hours', $grade->credit_hours) }}" required>
    </div>

    <div class="mb-3">
        <label for="grade" class="form-label">Grade (0 - 4.0)</label>
        <input type="number" step="0.01" name="grade" class="form-control" value="{{ old('grade', $grade->grade) }}" required>
    </div>

    <button type="submit" class="btn btn-primary">{{ $grade->id ? 'Update' : 'Save' }}</button>
</form>

@endsection
