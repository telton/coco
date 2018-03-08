@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Create New Assignment for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <courses-assignments-form 
        due-date={{ old('due_date') }}
        display-date={{ old('display_date') }}
        inline-template v-cloak>
        <form class="form-horizontal" method="POST" action="{{ route('courses.assignments.store', $course->slug) }}" enctype="multipart/form-data">
            {{ csrf_field() }} 
            <div class="card-body">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label"><strong>Name</strong></label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
    
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="control-label"><strong>Description</strong></label>
                    <div id="descriptionEditor"></div>
                    <input type="hidden" name="description" id="description" value="{{ old('description') }}" ref="description">

                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                {{--  Attachments  --}}
                <div ref="dropzone">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Files
                            <strong v-if="dropzone" class="text-warning">Drop submission files here!</strong>
                        </h3>
                        <div class="pull-right attachment-tools">
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

                {{--  Date pickers  --}}
                <div class="form-group d-lg-inline-block">
                    <label class="form-control-label"><strong>Due Date</strong></label>
                    <flat-pickr v-model="inputs.dueDate" :config="dueDateConfig" input-class="d-none" name="due_date" :required="true"></flat-pickr>
                    @if ($errors->has('due_date'))
                        <span class="help-block">
                            <strong>{{ $errors->first('due_date') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group d-lg-inline-block ml-lg-3">
                    <label class="form-control-label"><strong>Display Date</strong></label>
                    <flat-pickr v-model="inputs.displayDate" :config="displayDateConfig" input-class="d-none" name="display_date" :required="true"></flat-pickr>
                    @if ($errors->has('display_date'))
                        <span class="help-block">
                            <strong>{{ $errors->first('display_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" v-on:click="onSubmit()">Create</button>
            </div>
        </form>
    </courses-assignments-form>
</div>

@endsection

@section('aside')

    @include('includes.chat')

@endsection