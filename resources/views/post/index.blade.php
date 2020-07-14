@extends('layouts.app')

@section('content')
    <div class="container">
        <post-excerpt-list base-url="{{route('post.index')}}/" page="{{ $page }}" max-page="{{$maxPage}}"></post-excerpt-list>
    </div>
@endsection
