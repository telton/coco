@extends('layouts.app')

@section('content')

<div class="card" style="margin-top:15px;">
    <div class="card-header">
        My Courses
    </div>
    <div class="card-body">
        @if(count($courses) !== 0)
            @foreach($courses as $course)
                <div class="box">
                    {{ $course-> title }}
                </div>
            @endforeach
        @else
            <p>You are not registered for any courses!</p>
        @endif
    </div>
</div>

@endsection