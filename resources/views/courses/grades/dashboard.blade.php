@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')


<div class="card">
    <div class="card-header">
        <strong>Grades for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <courses-grades-dashboard inline-template v-cloak>
        <div class="card-body">
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
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gradeAssignment" v-on:click="onModalOpen()">
                                                            Enter Grade
                                                        </button>
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
                                                    <td></td>
                                                        <button class="btn btn-primary">Edit Grade</button>
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

            <!-- Grade Assignment Submission Modal -->
            <div class="modal fade" id="gradeAssignment" tabindex="-1" role="dialog" aria-labelledby="gradeAssignmentLabel" aria-hidden="true">
                <form class="form-horizontal" method="POST" action="{{ route('courses.grades.store', [$course->slug, $assignment]) }}">
                    {{ csrf_field() }}
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="gradeAssignmentLabel">Grade Assignment Submission</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="grade-input">
                                    <div class="input">
                                        <label for="grade" class="control-label"><strong>Grade</strong></label>
                                        <input id="grade" type="grade" class="form-control" name="grade" value="{{ old('grade') }}" required>
                        
                                        @if ($errors->has('grade'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('grade') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="input">
                                        <label for="letterGrade" class="control-label"><strong>Letter Grade</strong></label>
                                        <input id="letterGrade" type="letterGrade" class="form-control" name="letterGrade" value="{{ old('letterGrade') }}" required>
                        
                                        @if ($errors->has('letterGrade'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('letterGrade') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <label for="comments" class="control-label"><strong>Comments</strong></label>
                                <div id="commentsEditor" ref="commentsEditor"></div>
                                <input type="hidden" name="comments" id="comments" value="{{ old('description') }}" ref="comments">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" v-on:click="onSubmit()"><i class="fa fa-check"></i> Enter Grade</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </courses-grades-dashboard>   
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection