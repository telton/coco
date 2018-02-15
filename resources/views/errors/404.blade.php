@extends('layouts.errors')

@section('title')
    404
@endsection

@section('content')
    <p>
        The page could not be found! Return to the <a href="{{ url('/') }}">homepage</a>.
    </p>
@endsection