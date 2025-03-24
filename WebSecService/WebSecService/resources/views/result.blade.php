@extends('layouts.master')

@section('title', 'Exam Result')

@section('content')
    <div class="text-center">
        <h3>Your Exam Score: {{ $score }}</h3>
        <a href="{{ route('exam.index') }}" class="btn btn-primary mt-3">Retake Exam</a>
    </div>
@endsection
