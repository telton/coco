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
                <form class="form-horizontal" method="POST" action="{{ route('courses.assignments.submit', [$course->slug, $assignment]) }}" enctype="multipart/form-data">
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
                                        <div v-if="uploads.length == 0" class="alert alert-info" style="margin-bottom: 0;">
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