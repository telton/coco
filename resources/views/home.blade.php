@extends('layouts.app')

@section('content')

<div class="col-lg-6 offset-lg-3" style="margin-top: 20px;">
    <div class="card">
        <div class="card-header">
            My Courses
        </div>
        <div class="card-body courses-list">
            <table class="table table-striped">
                @if(count($courses) !== 0)
                    <p><strong>Courses for which you are:</strong> {{ ucfirst(Auth::user()->role->name) }}</p>
                    @foreach($courses as $course)
                        <tr>
                            <td>
                                <a href="{{ route('courses.show', $course->slug) }}" class="course-link">
                                    {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
                                </a>
                            </td>
                            @if (!Auth::user()->hasRole('instructor'))
                                <td>
                                    <strong>Instructor:</strong> {{ $course->instructor->name }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>
                            <p>You are not registered for any courses!</p>
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>

@endsection