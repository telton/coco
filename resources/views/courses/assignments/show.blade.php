@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>{{ $assignment->name }}:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <courses-assignments-show inline-template>
        <div class="card-body">
            <strong>Due Date:</strong> {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
            <input type="hidden" ref="description" value="{{ $assignment->description }}">
            <div id="descriptionViewer"></div>
            <div class="btn-toolbar">
                <a href="#" class="btn btn-primary"><i class="fa fa-save"></i> Submit Assignment</a>
            </div>
        </div>
    </courses-assignments-show>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection