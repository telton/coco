@extends('layouts.app')

@section('content')

<div class="card card-center">
    <div class="card-header">
        My Courses
    </div>
    <div class="card-body courses-list">
        <table class="table table-striped">
            @if(count($courses) !== 0)
                @foreach($courses as $course)
                    <tr>
                        <td>
                            <a href="{{ route('courses.show', $course->slug) }}">{{ $course-> title }}</a>
                        </td>
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

@endsection