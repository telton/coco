@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Edit Note {{ $note->title }} for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <edit-note :course="{{ $course }}" :note="{{ $note }}" v-cloak></edit-note>
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection