@extends('layouts.master')

@section('title', 'Grades List')

@section('content')

<h1>Grades List</h1>

<div class="row mb-3">
    <div class="col text-end">
        <a href="{{ route('grades_add') }}" class="btn btn-success">Add New Grade</a>
    </div>
</div>

@foreach($grades as $term => $gradesInTerm)
    @php
        $termCH = $gradesInTerm->sum('credit_hours');
        $termPoints = $gradesInTerm->sum(function($g) {
            return $g->credit_hours * $g->grade;
        });
        $termGPA = $termCH ? $termPoints / $termCH : 0;
    @endphp

    <h3>Term: {{ $term }}</h3>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Subject</th>
                <th>Credit Hours</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gradesInTerm as $grade)
            <tr>
                <td>{{ $grade->subject }}</td>
                <td>{{ $grade->credit_hours }}</td>
                <td>{{ $grade->grade }}</td>
                <td>
                    <a href="{{ route('grades_edit', $grade->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <a href="{{ route('grades_delete', $grade->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete {{ $grade->subject }}?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Credit Hours (CH):</strong> {{ $termCH }}</p>
    <p><strong>Term GPA:</strong> {{ number_format($termGPA, 2) }}</p>

@endforeach

<hr>
<h3>Cummulative Summary</h3>
<p><strong>Total Credit Hours (CCH):</strong> {{ $totalCH }}</p>
<p><strong>Cummulative GPA (CGPA):</strong> {{ number_format($CGPA, 2) }}</p>

@endsection
