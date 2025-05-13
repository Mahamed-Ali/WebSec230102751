@extends('layouts.master')

@section('title', 'Cryptography Form')

@section('content')
<div class="container mt-4">

    <!-- Page Header -->
    <div class="bg-primary text-white p-3 rounded mb-4 shadow">
        <h2 class="m-0">Cryptography Form</h2>
    </div>

    <!-- Form Card -->
    <div class="card border-primary shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('cryptography') }}" method="get">
                {{ csrf_field() }}

                <!-- Data Input -->
                <div class="row mb-2">
                    <div class="col">
                        <label for="data" class="form-label">Data:</label>
                        <textarea 
                            class="form-control" 
                            name="data" 
                            placeholder="Enter data here" 
                            rows="3" 
                            required>{{ $data }}</textarea>
                    </div>
                </div>

                <!-- Operation Selection -->
                <div class="row mb-2">
                    <div class="col">
                        <label for="action" class="form-label">Operation:</label>
                        <select class="form-control" name="action" required>
                            <option {{ $action == "Encrypt" ? 'selected' : '' }}>Encrypt</option>
                            <option {{ $action == "Decrypt" ? 'selected' : '' }}>Decrypt</option>
                            <option {{ $action == "Hash" ? 'selected' : '' }}>Hash</option>
                            <option {{ $action == "Sign" ? 'selected' : '' }}>Sign</option>
                            <option {{ $action == "Verify" ? 'selected' : '' }}>Verify</option>
                            <option {{ $action == "KeySend" ? 'selected' : '' }}>KeySend</option>
                            <option {{ $action == "KeyRecive" ? 'selected' : '' }}>KeyRecive</option>
                        </select>
                    </div>
                </div>

                <!-- Result Output -->
                <div class="row mb-2">
                    <div class="col">
                        <label for="result" class="form-label">Result:</label>
                        <textarea 
                            class="form-control" 
                            name="result" 
                            rows="3" 
                            placeholder="Result will appear here" 
                            readonly>{{ $result }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mb-2">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Result Status Card -->
    @if($status)
    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Operation Status</h5>
        </div>
        <div class="card-body bg-light">
            <strong>Result Status:</strong> {{ $status }}
        </div>
    </div>
    @endif

</div>
@endsection