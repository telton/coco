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
                        <button type="submit" class="btn btn-danger" ref="delete"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                @endif
            </div>
            
            <p><strong>Due Date:</strong> {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}</p>
            @if (Auth::user()->hasRole(['admin', 'instructor', 'grader', 'tutor']))
                <p><strong>Display Date:</strong> {{ $assignment->display_date->format('m/d/Y') }} at {{ $assignment->display_date->format('h:i A') }}</p>
            @endif
            <p><strong>Total Points: </strong> {{ $assignment->points }}</p>
            <p><strong>Description:</strong></p>
            <input type="hidden" ref="description" value="{{ $assignment->description }}">
            <div id="descriptionViewer" ref="descriptionViewer"></div>

            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title">Attachments</h3>
                </div>
                <table class="table table-striped">
                    <tbody>
                        @forelse($attachments as $attachment)
                            <tr>
                                <td class="attachment-icon">
                                    <i class="fa {{ $attachment->icon }}"></i>
                                </td>
                                <td>
                                    <a href="{{ route('courses.assignments.attachments.show', [$course->slug, $assignment, $attachment->id]) }}" target="_blank">{{ $attachment->name }}</a>
                                </td>
                                <td class="attachment-date">
                                    {{ $attachment->created_at->format('m/d/Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th>No Attachments</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (Auth::user()->hasRole('student'))
                <div class="btn-toolbar">
                    @if (!$assignment->submission())
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submitAssignment" v-on:click="onModalOpen()">
                            <i class="fa fa-save"></i> Submit Assignment
                        </button>
                    @else 
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#viewSubmission" v-on:click="onModalOpen()">
                            <i class="fa fa-search"></i> View Submission
                        </button>
                    @endif
                    @if ($assignment->submission() && $assignment->submission()->grade())
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewGrade" v-on:click="onModalOpen()">
                            <i class="fa fa-search"></i> View Grade
                        </button>
                    @endif
                </div>
            @endif

            <!-- Assignment Submission Modal -->
            <div class="modal fade" id="submitAssignment" tabindex="-1" role="dialog" aria-labelledby="submitAssignmentLabel" aria-hidden="true">
                <form class="form-horizontal" method="POST" action="{{ route('courses.assignments.submissions.store', [$course->slug, $assignment]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="submitAssignmentLabel">Submit Assignment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div ref="dropzone">
                                    <div class="card-header with-border">
                                        <h3 class="card-title">
                                            Files
                                            <strong v-if="dropzone" class="text-warning">Drop submission files here!</strong>
                                        </h3>
                                        <div class="submit-tools pull-right">
                                            <button v-on:click.prevent="addUpload()" type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div v-if="uploads.length == 0" class="alert alert-info">
                                            <strong>You don't have any files added!</strong> Add an file by clicking on the <i class="fa fa-plus"></i> above and to the right or by dragging and dropping them here.
                                        </div>

                                        <div class="hidden" ref="files"></div>
                                        <div v-if="uploads.length" class="card card-info">
                                            <div class="card-header with-border">
                                                <h3 class="card-title">Files to be Uploaded</h3>
                                            </div>
                                            <div class="card-body" style="padding-top: 5px;">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr v-for="upload in uploads">
                                                            <th valign="middle">
                                                                <div v-for="file in upload.files">
                                                                    @{{ file.name }}
                                                                </div>
                                                            </th>
                                                            <td class="text-right" valign="middle">
                                                                <button v-on:click.prevent="removeUpload(upload)" type="button" class="btn btn-sm btn-danger-outline"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="comments" class="control-label"><strong>Comments</strong></label>
                                <div id="commentsEditor" ref="commentsEditor"></div>
                                <input type="hidden" name="submitComments" id="submitComments" value="{{ old('description') }}" ref="submitComments">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" v-on:click="onSubmit()"><i class="fa fa-save"></i> Submit Assignment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if ($assignment->submission())
                <!-- Submission View Modal -->
                <div class="modal fade" id="viewSubmission" tabindex="-1" role="dialog" aria-labelledby="viewAssignmentLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewAssignmentLabel">View Assignment Submission</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card card-info">
                                    <div class="card-header with-border">
                                        <h3 class="card-title">Attachments</h3>
                                    </div>
                                    <table class="table table-striped">
                                        <tbody>
                                            @forelse($assignment->submission()->attachments() as $attachment)
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
                                </div>

                                <label for="comments" class="control-label"><strong>Comments</strong></label>
                                <div id="commentsViewer" ref="commentsViewer"></div>
                                <input type="hidden" name="viewComments" id="viewComments" value="{{ $assignment->submission()->comments }}" ref="viewComments">
                            </div>
                            <div class="modal-footer">
                                <p style="margin-bottom: 0; margin-right: 108px;"><strong>Submitted On:</strong> {{ $assignment->submission()->created_at->format('m/d/Y') }} at {{ $assignment->submission()->created_at->format('h:i A') }}</p>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                @if ($assignment->submission()->grade())
                    <!-- Submission Grade View Modal -->
                    <div class="modal fade" id="viewGrade" tabindex="-1" role="dialog" aria-labelledby="viewGradeLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewGradeLabel">View Submission Grade</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Points Earned:</strong> {{ $assignment->submission()->grade()->points_earned }} / {{ $assignment->points }}</p>
                                    <p><strong>Grade:</strong> {{ number_format($assignment->submission()->grade()->grade * 100, 2) }}%</p>
                                    <p><strong>Letter Grade:</strong> {{ $assignment->submission()->grade()->letter_grade }}</p>
                                    <label for="gradeComments" class="control-label"><strong>Comments</strong></label>
                                    <div id="gradeCommentsViewer" ref="gradeCommentsViewer"></div>
                                    <input type="hidden" name="viewGradeComments" id="viewGradeComments" value="{{ $assignment->submission()->grade()->comments }}" ref="viewGradeComments">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </courses-assignments-show>
</div>

@endsection