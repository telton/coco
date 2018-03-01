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
            <div class="assignments-toolbar">
                @if (Auth::user()->hasRole(['admin', 'instructor']))
                    <a href="{{ route('courses.assignments.edit', [$course->slug, $assignment]) }}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit Assignment</a>
                    <form action="{{ route('courses.assignments.destroy', [$course->slug, $assignment]) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                @endif
            </div>
            
            <p><strong>Due Date:</strong> {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}</p>
            <p><strong>Description:</strong></p>
            <input type="hidden" ref="description" value="{{ $assignment->description }}">
            <div id="descriptionViewer"></div>

            @if (Auth::user()->hasRole('student'))
                <div class="btn-toolbar">
                    <a href="#" class="btn btn-primary"><i class="fa fa-save"></i> Submit Assignment</a>
                </div>
            @endif
        </div>
    </courses-assignments-show>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection