@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{$post->title}}</h3>
            </div>
            <div class="card-body">
                <p>{{ nl2br($post->body)  }}</p>
            </div>
        </div>
    </div>


@endsection
