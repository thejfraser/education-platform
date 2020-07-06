@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($tags as $tag)
            <h2>{{$tag->name}}</h2>
            <p>{{$tag->posts->count()}} Post(s) -> <a href="{{route('tag.show', [$tag->slug])}}">View</a></p>
        @endforeach
    </div>
@endsection
