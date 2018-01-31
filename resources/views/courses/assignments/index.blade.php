@extends('layouts.app')

@section('content')

@include('includes.nav')

<div class="card">
    <div class="card-header">
        <strong>Assignments for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        
    </div>
</div>

@include('includes.chat')

@endsection