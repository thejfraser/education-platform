<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">{{$post->title}}</h3>
    </div>
    <div class="card-body">
        <p>{{ $post->excerpt  }}</p>
        <p class="text-right"><a href="{{ route('post.show', $post->slug) }}">Read More...</a></p>
    </div>
</div>
