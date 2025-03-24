<!-- @extends('layouts.master')

@section('title', 'MCQ Exam')

@section('content')
    <h2 class="text-center">MCQ Exam</h2>
    <a href="{{ route('questions.create') }}" class="btn btn-primary mb-3">Add New Question</a>

    <form action="{{ route('exam.submit') }}" method="POST">
        @csrf
        <div class="list-group">
            @foreach ($questions as $question)
                <div class="list-group-item">
                    <p><strong>{{ $question->question }}</strong></p>
                    @foreach (json_decode($question->options, true) as $key => $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}">
                            <label class="form-check-label">{{ $option }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success mt-3">Submit Exam</button>
    </form>
@endsection -->
