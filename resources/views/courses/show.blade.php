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

        <p style="margin-top: 2em; text-align:center;"><strong>Use the navigation panel to view the course assignments, your notes, grades, or access chat.</strong></p>
    </div>
</div>

@endsection