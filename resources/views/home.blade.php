@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        My Courses
    </div>
    <div class="card-body">
        @if(count($courses) !== 0)
            @foreach($courses as $course)
                <div class="box">
                    <a href="{{ route('courses.show', $course->slug) }}">{{ $course-> title }}</a>
                </div>
            @endforeach
        @else
            <p>You are not registered for any courses!</p>
        @endif
    </div>
</div>

@endsection