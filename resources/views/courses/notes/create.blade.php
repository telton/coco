@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Create New Note for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <courses-notes-form inline-template v-cloak>
            <form action="{{ route('courses.notes.store', $course->slug) }}" method="POST" class="form" role="form">
                {{ csrf_field() }}

                <div class="notes-title form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label"><strong>Title:</strong></label>
                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Give your note a title" required autofocus>

                    @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                    <label for="body" class="control-label"><strong>Body:</strong></label>
                    <div id="bodyEditor" ref="bodyEditor"></div>
                    <input type="hidden" name="body" id="body" value="{{ old('body') }}" ref="body">
                </div>

                <button type="submit" class="btn btn-primary pull-right" v-on:click="onSubmit()">Save</button>
            </form>
        </courses-notes-form>
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection