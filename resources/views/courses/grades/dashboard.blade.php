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
                                <form class="form-horizontal" method="POST" action="{{ route('courses.grades.store', [$course->slug, $assignment]) }}">
                                    {{ csrf_field() }}
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
                                                            <div class="btn-group" role="group">
                                                                <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                    <a class="dropdown-item" href="#gradeSubmission-{{$submission->id}}" data-toggle="modal" data-target="#gradeSubmission-{{$submission->id}}" v-on:click="onModalOpen({{ $submission->id }})">
                                                                        <i class="fa fa-edit"></i> Enter Grade
                                                                    </a>
                                                                    @if (Auth::user()->hasRole(['admin', 'instructor']))
                                                                        <form action="#" method="POST">
                                                                            {{ csrf_field() }}
                                                                            <button type="submit" class="dropdown-item" ref="deleteSubmission"><i class="fa fa-trash"></i> Delete Submission</button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Grade Assignment Submission Modal -->
                                                    <div class="modal fade" id="gradeSubmission-{{$submission->id}}" tabindex="-1" role="dialog" aria-labelledby="gradeSubmissionLabel-{{$submission->id}}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="gradeSubmissionLabel-{{$submission->id}}">Grade Assignment Submission</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="submission-details">
                                                                        <div class="margin-bottom-10">
                                                                            <p><strong>Student Name: </strong></p>
                                                                            {{ $submission->user->name }}
                                                                        </div>
                                                                        @if (!is_null($submission->file) || !is_null($submission->comments))
                                                                            @if (!is_null($submission->file))
                                                                                <div class="margin-bottom-10">
                                                                                    <p><strong>File: </strong></p>
                                                                                    <i class="fa {{ $submission->icon }}"></i>
                                                                                    <a href="{{ route('courses.assignments.attachments.show', [$course->slug, $assignment, $submission->id]) }}" target="_blank">{{ $submission->name }}</a>
                                                                                </div>
                                                                                
                                                                                <div class="margin-bottom-10">
                                                                                    <p><strong>Submitted At: </strong></p>
                                                                                    {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}
                                                                                </div>
                                                                            @endif
                                                                            @if (!is_null($submission->comments))
                                                                                <div class="submission-comments">
                                                                                    <p><strong>Comments:</strong></p>
                                                                                    <div id="submissionCommentsViewer-{{ $submission->id }}"></div>
                                                                                    <input type="hidden" value="{{ $submission->comments }}" name="submissionCommentsValue" id="submissionCommentsValue-{{ $submission->id }}" ref="submissionCommentsValue-{{ $submission->id }}"> 
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>

                                                                    <div class="grade-input bordered-gray">
                                                                        <div class="input">
                                                                            <label for="pointsEarned" class="control-label"><strong>Grade</strong></label>
                                                                            <div class="grade-input-area">
                                                                                <input id="pointsEarned" type="text" class="form-control" name="pointsEarned" value="{{ old('pointsEarned') }}" v-model="pointsEarned" ref="pointsEarned" placeholder="Earned" required> 
                                                                                <span class="divider">/</span> <span class="total-points">{{ $assignment->points }}</span>
                                                                                <input id="totalPoints" type="hidden" name="totalPoints" value="{{ $assignment->points }}" ref="totalPoints">
                                                                            </div>
                                                                        </div>

                                                                        <div class="input">
                                                                            <label for="grade" class="control-label"><strong>Computed Grade</strong></label>
                                                                            <input type="hidden" :value="computedGrade" id="grade" name="grade" class="form-control" ref="grade">
                                                                            <span>@{{ percentGrade }}%</span>

                                                                            @if ($errors->has('grade'))
                                                                                <span class="help-block">
                                                                                    <strong>{{ $errors->first('grade') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        <div class="input">
                                                                            <label for="letterGrade" class="control-label"><strong>Letter Grade</strong></label>
                                                                            <input id="letterGrade" type="text" class="form-control" name="letterGrade" value="{{ old('letterGrade') }}" placeholder="A+" required>
                                                            
                                                                            @if ($errors->has('letterGrade'))
                                                                                <span class="help-block">
                                                                                    <strong>{{ $errors->first('letterGrade') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <label for="comments" class="control-label" style="margin-bottom: 0"><strong>Comments</strong></label>
                                                                        <div id="gradeCommentsEditor-{{ $submission->id }}" ref="gradeCommentsEditor-{{ $submission->id }}"></div>
                                                                        <input type="hidden" value="{{ old('comments') }}" name="gradeComments" id="gradeComments-{{ $submission->id }}" ref="gradeComments-{{ $submission->id }}">
                                                                        <input type="hidden" name="submissionId" id="submissionId" value="{{$submission->id}}" ref="id"> 
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary" v-on:click="onSubmit({{ $submission->id }})"><i class="fa fa-check"></i> Enter Grade</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
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
                                            Date Graded
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
                                                        {{ round($submission->grade($assignment->id)->grade * 100, 2) }}%
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ strtoupper($submission->grade($assignment->id)->letter_grade) }}
                                                    </td>
                                                    <td>
                                                        {{ $submission->updated_at->format('m/d/Y') }} at {{ $submission->updated_at->format('h:i A') }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="fa fa-edit"></i> Edit Grade
                                                                </a>
                                                                @if (Auth::user()->hasRole(['admin', 'instructor']))
                                                                    <form action="{{ route('courses.grades.destroy', [$course->slug, $submission->grade($assignment->id)]) }}" method="POST">
                                                                        {{ csrf_field() }}
                                                                        <button type="submit" class="dropdown-item" ref="deleteGrade"><i class="fa fa-trash"></i> Delete Grade</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
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
                            <p><strong>There are no assignment submissions that have been graded yet.</strong></p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </courses-grades-dashboard>   
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection