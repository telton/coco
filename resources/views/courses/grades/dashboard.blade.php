@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

{{--  Grades that need approved.  --}}
@if (Auth::user()->hasRole(['admin', 'instructor']))
    <div class="card">
        <div class="card-header">
            <strong>Grades that need to be approved</strong>
        </div>
        <div class="card-body">
            @forelse ($assignments['unapproved'] as $assignment)
                <div class="card">
                    <div class="card-header">
                        <strong>Assignment: <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">{{ $assignment->name }}</a></strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <th>
                                    Student Name
                                </th>
                                <th>
                                    Date Submitted
                                </th>
                                <th>
                                    Graded By
                                </th>
                                <th>
                                    Grade
                                </th>
                                <th>
                                    Letter Grade
                                </th>
                                <th style="width: 98px;">
                                    Actions
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($submissions['unapproved'] as $submission)
                                    @if ($assignment->id === $submission->assignment_id)
                                        <tr>
                                            <td>
                                                {{ $submission->user->name }}
                                            </td>
                                            <td>
                                                {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}
                                            </td>
                                            <td>
                                                {{ $submission->grade($assignment->id)->grader->name }}
                                            </td>
                                            <td>
                                                Grade
                                            </td>
                                            <td>
                                                Letter Grade
                                            </td>
                                            <td>
                                                <button class="btn btn-primary">Approve</button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <p><strong>There are no assignment submissions that require approval.</strong></p>
                </div>
            @endforelse
        </div>
    </div>
@endif

{{--  Grades that need to be entered.  --}}
<div class="card">
    <div class="card-header">
        <strong>Submissions that need to be graded</strong>
    </div>
    <div class="card-body">
        @forelse ($assignments['ungraded'] as $assignment)
            <div class="card">
                <div class="card-header">
                    <strong>Assignment: <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">{{ $assignment->name }}</a></strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th>
                                Student Name
                            </th>
                            <th>
                                Date Submitted
                            </th>
                            <th style="width: 98px;">
                                Actions
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($submissions['ungraded'] as $submission)
                                @if ($assignment->id === $submission->assignment_id)
                                    <tr>
                                        <td>
                                            {{ $submission->user->name }}
                                        </td>
                                        <td>
                                            {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary">Enter Grade</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <p><strong>There are no assignment submissions that require grading.</strong></p>
            </div>
        @endforelse
    </div>
</div>

{{--  Grades that are already entered and approved.  --}}
<div class="card">
    <div class="card-header">
        <strong>Completed submissions</strong>
    </div>
    <div class="card-body">
        @forelse ($assignments['completed'] as $assignment)
            <div class="card">
                <div class="card-header">
                    <strong>Assignment: <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">{{ $assignment->name }}</a></strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th>
                                Student Name
                            </th>
                            <th>
                                Date Submitted
                            </th>
                            <th>
                                Graded By
                            </th>
                            <th>
                                Grade
                            </th>
                            <th>
                                Letter Grade
                            </th>
                            <th>
                                Date Approved
                            </th>
                            <th style="width: 98px;">
                                Actions
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($submissions['completed'] as $submission)
                                @if ($assignment->id === $submission->assignment_id)
                                    <tr>
                                        <td>
                                            {{ $submission->user->name }}
                                        </td>
                                        <td>
                                            {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}
                                        </td>
                                        <td>
                                            {{ $submission->grade($assignment->id)->grader->name }}
                                        </td>
                                        <td>
                                            Grade
                                        </td>
                                        <td>
                                            Letter Grade
                                        </td>
                                        <td>
                                            {{ $submission->updated_at->format('m/d/Y') }} at {{ $submission->updated_at->format('h:i A') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary">Approve</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <p><strong>There are no assignment submissions that have been graded and approved yet.</strong></p>
            </div>
        @endforelse
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection