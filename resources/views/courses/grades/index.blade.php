@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Grades for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <th>
                    Assignment Name
                </th>
                <th>
                    Due Date
                </th>
                <th>
                    Date Submitted
                </th>
                <th>
                    Grade
                </th>
                <th>
                    Letter Grade
                </th>
            </thead>
            <tbody>
                @foreach($course->assignments as $assignment)
                    <tr>
                        <td>
                            <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">
                                <strong>{{ $assignment->name }}</strong>
                            </a> 
                        </td>
                        <td>
                            {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
                        </td>
                        <td>
                            @if ($assignment->submission())
                                {{ $assignment->submission()->created_at->format('m/d/Y') }} at {{ $assignment->submission()->created_at->format('h:i A') }}
                            @endif
                        </td>
                        <td>
                            @if ($assignment->submission() && $assignment->submission()->grade($assignment->id))
                                {{ round($assignment->submission()->grade($assignment->id)->grade * 100, 2) }}%
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($assignment->submission() && $assignment->submission()->grade($assignment->id))
                                {{ $assignment->submission()->grade($assignment->id)->letter_grade }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection