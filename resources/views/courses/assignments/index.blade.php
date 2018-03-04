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
        <div class="btn-toolbar assignments-toolbar">
            @if (Auth::user()->hasRole(['admin', 'instructor']))
                <a href="{{ route('courses.assignments.create', $course->slug) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Issue Assignment</a>
            @endif
        </div>
        @if (count($course->assignments) === 0)
            <p>There are zero assignments for this course!</p>
        @else
            <courses-assignments-index inline-template v-cloak>
                <table class="table table-striped">
                    <thead>
                        <th>
                            Name
                        </th>
                        <th>
                            Due Date
                        </th>
                        @if (Auth::user()->hasRole(['admin', 'instructor']))
                            <th style="width:98px;">
                                Actions
                            </th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($course->assignments as $assignment)
                            <tr>
                                <td>
                                    <a id="assignment-name" href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }} ">
                                        <strong>{{ $assignment->name }}</strong>
                                    </a>
                                </td>
                                <td>
                                    {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
                                </td>
                                @if (Auth::user()->hasRole(['admin', 'instructor']))
                                    <td>
                                        <form action="{{ route('courses.assignments.destroy', [$course->slug, $assignment]) }}" method="POST">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger" ref="delete"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </courses-assignments-index>
        @endif
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection