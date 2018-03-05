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
            <p><strong>Description:</strong></p>
            <input type="hidden" ref="description" value="{{ $assignment->description }}">
            <div id="descriptionViewer"></div>

            @if (Auth::user()->hasRole('student'))
                <div class="btn-toolbar">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submitAssignment" v-on:click="onModalOpen()">
                        <i class="fa fa-save"></i> Submit Assignment
                    </button>
                </div>
            @endif

            <!-- Assignment Submission Modal -->
            <div class="modal fade" id="submitAssignment" tabindex="-1" role="dialog" aria-labelledby="submitAssignmentLabel" aria-hidden="true">
                <form class="form-horizontal" method="POST" action="{{ route('courses.assignments.submit', [$course->slug, $assignment]) }}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="submitAssignmentLabel">Submit Assignment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="assignmentFile">
                                        <label class="custom-file-label" for="assignmentFile">Choose file</label>
                                    </div>
                                </div>

                                <label for="comments" class="control-label"><strong>Comments</strong></label>
                                <div id="commentsEditor"></div>
                                <input type="hidden" name="comments" id="comments" value="{{ old('description') }}" ref="comments">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" v-on:click="onSubmit()"><i class="fa fa-save"></i> Submit Assignment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </courses-assignments-show>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection