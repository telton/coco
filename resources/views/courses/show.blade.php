@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Home:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        @if ($course->description)
            <p>{{ $course->description }}</p>
        @else
            <span class="alert alert-info flex-row-center">There isn't a description for this course!</span>
        @endif
    </div>
</div>

@endsection