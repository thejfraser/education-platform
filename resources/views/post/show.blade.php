@extends('layouts.app')

@section('content')
    <div class="container">
        <section>
            <header class="mb-4">
                <h1>{{$post->title}}</h1>
                <p><em>Written by {{$post->author->name}}</em>@if ($post->published_at !== null) &bull; Published <time>{{$post->published_at->format('l jS F Y H:i')}}</time>@endif</p>
            </header>
            <main class="row align-items-stretch">
                <div class="col-12 col-md-8 col-lg-9">
                    {!! $post->body !!}
                </div>
                <div class="col-12 col-md-4 col-lg-3 bg-light py-3">
                    @can('update', $post)
                        <a href="{{route('post.edit', $post->id)}}" class="btn btn-sm btn-outline-primary mb-2 w-100">Update Post</a>
                    @endcan
                </div>
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
