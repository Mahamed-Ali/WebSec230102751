@extends('layouts.master')
@section('title', 'multable')
@section('content')    
    @php($j = 5)
    <div class="card m-4 col-sm-2">
        <div class="card-header"> TrancScript</div>
        <div class="card-body">
            <table>
                @foreach 
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
            </table>
        </div>
    </div>
@endsection
   
