@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>View Note for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <courses-notes-show inline-template v-cloak>
            <div class="card">
                <div class="notes-toolbar">
                    @if (Auth::user()->id === $note->user_id)
                        <a href="{{ route('courses.notes.edit', [$course->slug, $note]) }}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit Note</a>
                        <a class="btn btn-primary" href="#getLink" data-toggle="modal" data-target="#getLink">
                            <i class="fa fa-clipboard"></i> Get Link
                        </a>
                        <a class="btn btn-info" href="{{ route('courses.notes.export', [$course->slug, $note]) }}">
                            <i class="fa fa-file-pdf-o"></i> Export to PDF
                        </a>
                        <form action="{{ route('courses.notes.destroy', [$course->slug, $note]) }}" method="POST" ref="deleteNoteForm">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger" ref="delete" v-on:click.prevent="onDelete()"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    @endif
                </div>

                <p><strong>Title:</strong> {{ $note->title }}</p>
                <div ref="bodyViewer"></div>
                <input type="hidden" value="{{ $note->body }}" ref="body">
                
                <!-- Get Link Modal -->
                <div class="modal fade" id="getLink" tabindex="-1" role="dialog" aria-labelledby="getLinkLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="getLinkLabel">Get Shareable Link</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body shareable-link">
                                <label for="slug" class="control-label"><strong>Link:</strong></label>
                                <input class="form-control" id="slug" name="slug" type="text" value="{{ route('courses.notes.edit', [$course->slug, $note]) }}" ref="slug" readonly>
                                <button class="btn btn-default" ref="copyToClipboard" id="copyToClipboard" v-on:click.prevent="copyToClipboard()" data-toggle="tooltip" title="Copy to clipboard" data-placement="bottom">
                                    <i class="fa fa-clipboard"></i>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </courses-notes-show>
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection