<post-excerpt href="{{ route('post.show', $post->slug) }}">
    <template v-slot:title="title">{{$post->title}}</template>
    {{$post->excerpt}}
</post-excerpt>
