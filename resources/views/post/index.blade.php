@extends('layouts.app')

@section('content')
    <div class="container">
        <post-excerpt-list page="{{ $page }}" max-page="{{$maxPage}}"></post-excerpt-list>
    </div>
@endsection
