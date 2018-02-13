@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Assignments for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        @if (Auth::user()->hasRole(['admin', 'instructor']))
            <a href="{{ route('courses.assignments.create', $course->slug) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Issue Assignment</a>
        @endif
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection