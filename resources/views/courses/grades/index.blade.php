@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

@section('content')

<courses-grades-index inline-template v-cloak>
    <div class="card">
        <div class="card-header">
            <strong>Grades for:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
        </div>
        <div class="card-body">
        @if (count($course->visibleAssignments) !== 0)
                <div class="btn-toolbar pull-right" style="margin-bottom: 15px;">
                    <a href="{{ route('courses.grades.export', $course->slug) }}" class="btn btn-primary">Export Grades to CSV</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <th>
                            Assignment Name
                        </th>
                        <th>
                            Due Date
                        </th>
                        <th>
                            Date Submitted
                        </th>
                        <th>
                            Grade
                        </th>
                        <th>
                            Letter Grade
                        </th>
                        <th>
                            Actions
                        </th>
                    </thead>
                    <tbody>
                        @foreach($course->visibleAssignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('courses.assignments.show', [$course->slug, $assignment]) }}">
                                        <strong>{{ $assignment->name }}</strong>
                                    </a> 
                                </td>
                                <td>
                                    {{ $assignment->due_date->format('m/d/Y') }} at {{ $assignment->due_date->format('h:i A') }}
                                </td>
                                <td>
                                    @if ($assignment->submission())
                                        {{ $assignment->submission()->created_at->format('m/d/Y') }} at {{ $assignment->submission()->created_at->format('h:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($assignment->submission() && $assignment->submission()->grade())
                                        {{ round($assignment->submission()->grade($assignment->id)->grade * 100, 2) }}%
                                    @endif
                                </td>
                                <td>
                                    @if ($assignment->submission() && $assignment->submission()->grade())
                                        {{ $assignment->submission()->grade($assignment->id)->letter_grade }}
                                    @endif
                                </td>
                                @if ($assignment->submission() && $assignment->submission()->grade())
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewGrade" v-on:click="onModalOpen()">
                                            <i class="fa fa-search"></i> View
                                        </button>

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
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <span class="alert alert-info flex-row-center">There aren't any grades visible yet!</span>
            @endif
        </div>
    </div>
</courses-grades-index>

@endsection


@section('aside')

    @include('includes.chat')

@endsection