@extends('layouts.app')

@section('content')
    <div class="container">
        <section>
            <header class="mb-4">
                <h1>{{$post->title}}</h1>
                <p><em>Written by {{$post->author->name}}</em></p>
            </header>
            <main>
                {!! nl2br($post->body)  !!}
            </main>
            <footer>
                @forelse($post->tags as $tag)
                    <a href="{{route('tag.show', $tag->slug)}}" class="btn btn-sm btn-light p-2 m-2 border">{{$tag->name}}</a>
                @empty
                @endforelse
            </footer>
        </section>
    </div>
@endsection
