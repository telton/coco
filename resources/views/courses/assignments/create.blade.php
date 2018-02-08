@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
        <div class="card-header">
            <strong>Create New Assignment for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
        </div>
        <courses-assignments-form inline-template>
            <form class="form-horizontal" method="POST" action="{{ route('courses.assignments.store', $course->slug) }}">
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
                        <input type="hidden" name="description" id="description" value="{{ old('description') }}">

                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                        <label for="due_date" class="control-label"><strong>Due Date</strong></label>
                        <input id="due_date" type="text" class="form-control" name="due_date" value="{{ old('due_date') }}" required>

                        @if ($errors->has('due_date'))
                            <span class="help-block">
                                <strong>{{ $errors->first('due_date') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('display_date') ? ' has-error' : '' }}">
                        <label for="display_date" class="control-label"><strong>Display Date</strong></label>
                        <input id="display_date" type="text" class="form-control" name="diplay_date" value="{{ old('diplay_date') }}" required>

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