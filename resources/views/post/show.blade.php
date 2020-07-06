@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{$post->title}}</h3>
            </div>

            <div class="p-4 bg-info">
                <p class="m-0">Written By {{$post->author->name}}</p>
            </div>

            <div class="card-body">
                <p>{!! nl2br($post->body)  !!}</p>
            </div>

            <div class="card-footer">
                @forelse($post->tags as $tag)
                    <a href="{{route('tag.show', $tag->slug)}}" class="btn btn-sm btn-light p-2 m-2 border">{{$tag->name}}</a>
                @empty
                @endforelse
            </div>
        </div>
    </div>


@endsection
