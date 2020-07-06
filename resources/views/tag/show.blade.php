@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{$tag->name}}</h3>
            </div>

            <div class="card-body">
                @forelse($posts as $post)
                    <x-list-post :post="$post" />
                @empty
                @endforelse
            </div>
        </div>
    </div>


@endsection
