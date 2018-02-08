@extends('layouts.app')

@section('nav')
    
    @include('includes.nav')

@endsection

<div class="card">
    <div class="card-header">
        <strong>Home:</strong> {{ $course->subject }}{{ $course->course_number }} - {{ sprintf('%02d', $course->section) }}: {{ $course->title }}
    </div>
    <div class="card-body">
        <p>Lorem ipsum dolor sit amet, affert docendi nam cu, dicunt volumus at qui. Qui ex eros perfecto, eros erant has te, pri id reque offendit 
        insolens. Ex tritani ancillae eos, sea ne recusabo explicari repudiare. Mea veri etiam maluisset et, ei timeam detracto scribentur qui. Sit 
        legere percipitur no, id vim eirmod consequuntur. Ei pro voluptua corrumpit.</p>

        <p>Eu falli timeam blandit vis, ut malorum probatus qui. Vis ad odio clita patrioque, audire repudiare ad nam, minim eruditi constituto his ne. 
        Omnis sanctus no sed, sea expetenda assentior vulputate no. Diam duis doctus in vel, ad primis aeterno euismod nec. Nullam noster tractatos 
        te mei, duo an inermis suscipit apeirian.</p>
    </div>
</div>

@endsection


@section('aside')

    @include('includes.chat')

@endsection