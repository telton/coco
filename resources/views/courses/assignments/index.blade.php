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
        <div class="btn-toolbar" id="assignments-toolbar">
            @if (Auth::user()->hasRole(['admin', 'instructor']))
                <a href="{{ route('courses.assignments.create', $course->slug) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Issue Assignment</a>
            @endif
        </div>
        @if (count($course->assignments) === 0)
            <p>There are zero assignments for this course!</p>
        @else
            <table class="table table-striped">
                <thead>
                    <th>
                        Name
                    </th>
                    <th>
                        Due Date
                    </th>
                </thead>
                <tbody>
                    @foreach($course->assignments as $assignment)
                        <tr>
                            <td>
                                <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }} ">
                                    {{ $assignment->name }}
                                </a>
                            </td>
                            <td>
                                {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection