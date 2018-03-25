@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>My Notes for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <div class="btn-toolbar assignments-toolbar">
            <a href="{{ route('courses.notes.create', $course->slug) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create New Note</a>
        </div>
        @if (count($notes) !== 0)
            <courses-notes-index inline-template v-cloak>
                <table class="table table-striped">
                    <thead>
                        <th>
                            Title
                        </th>
                        <th>
                            Created At
                        </th>
                        <th>
                            Last Modified
                        </th>
                        <th>
                            Actions
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($notes as $note)
                            <tr>
                                <td>
                                    <a href="{{ route('courses.notes.show', [$course->slug, $note]) }}"><strong>{{ $note->title }}</strong></a>
                                </td>
                                <td>
                                    {{ $note->created_at->format('m/d/Y') }} at {{ $note->created_at->format('h:i A') }}
                                </td>
                                <td>
                                    {{ $note->updated_at->format('m/d/Y') }} at {{ $note->updated_at->format('h:i A') }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#getLink-{{ $note->id }}" data-toggle="modal" data-target="#getLink-{{ $note->id }}">
                                                <i class="fa fa-clipboard"></i> Get Link
                                            </a>
                                            <a class="dropdown-item" href="{{ route('courses.notes.edit', [$course->slug, $note]) }}"><i class="fa fa-edit"></i> Edit</a>
                                            <form action="{{ route('courses.notes.destroy', [$course->slug, $note]) }}" id="deleteNoteForm-{{ $note->id }}" method="POST" ref="deleteNoteForm">
                                                {{ csrf_field() }}
                                                <button type="submit" class="dropdown-item" ref="deleteNote" v-on:click.prevent="onDelete({{ $note->id }})"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Get Link Modal -->
                                    <div class="modal fade" id="getLink-{{ $note->id }}" tabindex="-1" role="dialog" aria-labelledby="getLinkLabel-{{ $note->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="getLinkLabel-{{ $note->id }}">Get Shareable Link</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body shareable-link">
                                                    <label for="slug" class="control-label"><strong>Link:</strong></label>
                                                    <input class="form-control" id="slug" name="slug" type="text" value="{{ route('courses.notes.edit', [$course->slug, $note]) }}" ref="slug" readonly>
                                                    <button class="btn btn-default" ref="copyToClipboard-{{ $note->id }}" id="copyToClipboard-{{ $note->id }}" v-on:click.prevent="copyToClipboard({{ $note->id }})" data-toggle="tooltip" title="Copy to clipboard" data-placement="bottom">
                                                        <i class="fa fa-clipboard"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </courses-notes-index>
        @else
            <span class="alert alert-info flex-center justify-center"><strong>You do not have any notes created yet!</strong></span>
        @endif
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection