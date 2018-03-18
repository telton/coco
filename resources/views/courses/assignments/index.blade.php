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
        @if (count($course->visibleAssignments) !== 0)
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
                            <th>
                                Display Date
                            </th>
                            <th style="width:98px;">
                                Actions
                            </th>
                        @endif
                    </thead>
                    <tbody>
                    @if (Auth::user()->hasRole(['student', 'tutor', 'grader']))
                            @foreach($course->visibleAssignments as $assignment)
                                <tr>
                                    <td>
                                        <a id="assignment-name" href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }} ">
                                            <strong>{{ $assignment->name }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        @elseif (Auth::user()->hasRole(['admin', 'instructor']))
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
                                    <td>
                                        {{ $assignment->display_date->format('m/d/Y') }} at {{ $assignment->display_date->format('h:i A') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="{{ route('courses.assignments.edit', [$course->slug, $assignment]) }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('courses.assignments.destroy', [$course->slug, $assignment]) }}" method="POST" id="deleteForm-{{ $assignment->id }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="dropdown-item" ref="delete" v-on:click.prevent="onDelete({{ $assignment->id }})"><i class="fa fa-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </courses-assignments-index>
        @else 
            <span class="alert alert-info flex-row-center">There aren't any assignments visible yet!</span>
        @endif
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection