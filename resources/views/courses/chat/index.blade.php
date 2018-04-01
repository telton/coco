@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card chat-card">
    <div class="card-header">
        <strong>Chat for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body chat-body" id="chatBody">
        <chat-messages slug="{{ $course->slug }}"></chat-messages>
    </div>
    <div class="card-footer chat-footer">
        <chat-form :user="{{ Auth::user() }}"
            slug="{{ $course->slug }}"
        ></chat-form>
    </div>
</div>

@endsection