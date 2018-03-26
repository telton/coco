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
                                                        <div class="btn-group" role="group">
                                                            <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                <a class="dropdown-item" href="#gradeSubmission-{{ $submission->id }}" data-toggle="modal" data-target="#gradeSubmission-{{ $submission->id }}" v-on:click="onModalOpen({{ $submission->id }})">
                                                                    <i class="fa fa-edit"></i> Enter Grade
                                                                </a>
                                                                @if (Auth::user()->hasRole(['admin', 'instructor']))
                                                                    <form action="{{ route('courses.assignments.submissions.destroy', [$course->slug, $assignment, $submission]) }}" method="POST">
                                                                        {{ csrf_field() }}
                                                                        <button type="submit" class="dropdown-item" ref="deleteSubmission"><i class="fa fa-trash"></i> Delete Submission</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Grade Assignment Submission Modal -->
                                                        <form class="form-horizontal" method="POST" action="{{ route('courses.grades.store', [$course->slug, $assignment]) }}">
                                                            {{ csrf_field() }}
                                                            <div class="modal fade" id="gradeSubmission-{{ $submission->id }}" tabindex="-1" role="dialog" aria-labelledby="gradeSubmissionLabel-{{ $submission->id }}" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="gradeSubmissionLabel-{{ $submission->id }}">Grade Assignment Submission</h5>
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
                                                                                <table class="table table-striped">
                                                                                    <tbody>
                                                                                        @forelse($submission->attachments() as $attachment)
                                                                                            <tr>
                                                                                                <td class="attachment-icon">
                                                                                                    <i class="fa {{ $attachment->icon }}"></i>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <a href="{{ route('courses.assignments.attachments.show', [$course->slug, $assignment, $attachment->id]) }}" target="_blank">{{ $attachment->name }}</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @empty
                                                                                            <tr>
                                                                                                <th>No Attachments</th>
                                                                                            </tr>
                                                                                        @endforelse
                                                                                    </tbody>
                                                                                </table>
                                                                                @if (!is_null($submission->comments))
                                                                                    <div class="submission-comments">
                                                                                        <p><strong>Comments:</strong></p>
                                                                                        <div id="submissionCommentsViewer-{{ $submission->id }}"></div>
                                                                                        <input type="hidden" value="{{ $submission->comments }}" name="submissionCommentsValue" id="submissionCommentsValue-{{ $submission->id }}" ref="submissionCommentsValue-{{ $submission->id }}"> 
                                                                                    </div>
                                                                                @endif

                                                                                <p><strong>Submitted On:</strong> {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}</p>
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
                                                                                <input type="hidden" name="submissionId" id="submissionId" value="{{ $submission->id }}" ref="id"> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary" v-on:click="onSubmit({{ $submission->id }})"><i class="fa fa-check"></i> Enter Grade</button>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>   
                                                        </form>
                                                    </td>                                                     
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info flex-center">
                            <p><strong>There are no assignment submissions that require grading.</strong></p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{--  Grades that are already entered and approved.  --}}
            <div class="card">
                <div class="completed-submissions-card-header card-header">
                    <strong>Completed submissions</strong>
                    @if (count($assignments['completed']) !== 0)
                        <a href="{{ route('courses.grades.dashboard.export', [$course->slug]) }}" class="btn btn-success pull-right">Export all to CSV</a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse ($assignments['completed'] as $assignment)
                        <div class="card grade-card">
                            <div class="card-header">
                                <strong>Assignment: <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">{{ $assignment->name }}</a></strong>
                                <a href="{{ route('coruses.assignments.grades.export', [$course->slug, $assignment]) }}" class="btn btn-primary pull-right">Export to CSV</a>
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
                                                        {{ $submission->grade()->grader->name }}
                                                    </td>
                                                    <td>
                                                        {{ round($submission->grade()->grade * 100, 2) }}%
                                                    </td>
                                                    <td>
                                                        {{ strtoupper($submission->grade()->letter_grade) }}
                                                    </td>
                                                    <td>
                                                        {{ $submission->updated_at->format('m/d/Y') }} at {{ $submission->updated_at->format('h:i A') }}
                                                    </td>
                                                    <td>
                                                        <courses-grades-form points-earned="{{ $submission->grade()->points_earned }}" inline-template v-cloak>
                                                            <div>
                                                                <div class="btn-group" role="group">
                                                                    <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Actions
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                        <a class="dropdown-item" href="#editGrade-{{ $submission->id }}" data-toggle="modal" data-target="#editGrade-{{ $submission->id }}" v-on:click="onModalOpen({{ $submission->id }})">
                                                                            <i class="fa fa-edit"></i> Edit Grade
                                                                        </a>
                                                                        @if (Auth::user()->hasRole(['admin', 'instructor']))
                                                                            <form action="{{ route('courses.grades.destroy', [$course->slug, $assignment, $submission->grade()]) }}" method="POST">
                                                                                {{ csrf_field() }}
                                                                                <button type="submit" class="dropdown-item" ref="deleteGrade"><i class="fa fa-trash"></i> Delete Grade</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            
                                                                <!-- Edit Grade Assignment Submission Modal -->
                                                                <form class="form-horizontal" method="POST" action="{{ route('courses.grades.update', [$course->slug, $assignment, $submission->grade()]) }}">
                                                                    {{ csrf_field() }}
                                                                    <div class="modal fade" id="editGrade-{{ $submission->id }}" tabindex="-1" role="dialog" aria-labelledby="editGradeLabel-{{ $submission->id }}" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="editGradeLabel-{{ $submission->id }}">Edit Grade</h5>
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
                                                                                        <table class="table table-striped">
                                                                                            <tbody>
                                                                                                @forelse($submission->attachments() as $attachment)
                                                                                                    <tr>
                                                                                                        <td class="attachment-icon">
                                                                                                            <i class="fa {{ $attachment->icon }}"></i>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <a href="{{ route('courses.assignments.attachments.show', [$course->slug, $assignment, $attachment->id]) }}" target="_blank">{{ $attachment->name }}</a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @empty
                                                                                                    <tr>
                                                                                                        <th>No Attachments</th>
                                                                                                    </tr>
                                                                                                @endforelse
                                                                                            </tbody>
                                                                                        </table>
                                                                                        @if (!is_null($submission->comments))
                                                                                            <div class="submission-comments">
                                                                                                <p><strong>Comments:</strong></p>
                                                                                                <div id="submissionCommentsViewer-{{ $submission->id }}"></div>
                                                                                                <input type="hidden" value="{{ $submission->comments }}" name="submissionCommentsValue" id="submissionCommentsValue-{{ $submission->id }}" ref="submissionCommentsValue-{{ $submission->id }}"> 
                                                                                            </div>
                                                                                        @endif

                                                                                        <p><strong>Submitted On:</strong> {{ $submission->created_at->format('m/d/Y') }} at {{ $submission->created_at->format('h:i A') }}</p>
                                                                                    </div>

                                                                                    <div class="grade-input bordered-gray">
                                                                                        <div class="input">
                                                                                            <label for="pointsEarned" class="control-label"><strong>Grade</strong></label>
                                                                                            <div class="grade-input-area">
                                                                                                <input id="pointsEarned" type="text" class="form-control" name="pointsEarned" value="{{ old('pointsEarned') }}" v-model="points_earned" ref="pointsEarned" placeholder="Earned" required> 
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
                                                                                            <input id="letterGrade" type="text" class="form-control" name="letterGrade" value="{{ old('letterGrade', $submission->grade()->letter_grade) }}" placeholder="A+" required>
                                                                            
                                                                                            @if ($errors->has('letterGrade'))
                                                                                                <span class="help-block">
                                                                                                    <strong>{{ $errors->first('letterGrade') }}</strong>
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>

                                                                                        <label for="comments" class="control-label" style="margin-bottom: 0"><strong>Comments</strong></label>
                                                                                        <div id="gradeEditCommentsEditor-{{ $submission->id }}" ref="gradeEditCommentsEditor-{{ $submission->id }}"></div>
                                                                                        <input type="hidden" value="{{ old('comments', $submission->grade()->comments) }}" name="gradeEditComments" id="gradeEditComments-{{ $submission->id }}" ref="gradeEditComments-{{ $submission->id }}">
                                                                                        <input type="hidden" name="submissionId" id="submissionId" value="{{ $submission->id }}" ref="id"> 
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" class="btn btn-primary" v-on:click="onSubmit({{ $submission->id }})"><i class="fa fa-check"></i> Save Grade</button>
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>   
                                                                </form>
                                                            </div>
                                                        </courses-grades-form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info flex-center">
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