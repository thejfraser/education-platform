@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($posts as $post)
            <x-list-post :post="$post" />
        @endforeach
    </div>
@endsection
