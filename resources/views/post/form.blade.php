@extends('layouts.app')

@section('content')
    <div class="container">
        @if($post->id > 0)
            <h1>Edit Post</h1>
        @else
            <h1>Create Post</h1>
        @endif

        @if(session()->get('updated') ?? false)
            <div class="alert alert-success">Post Updated Successfully</div>
        @endif
        @if(session()->get('created') ?? false)
            <div class="alert alert-success">Post Created Successfully</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">There were errors in your save</div>
        @endif

        <form action="@if($post->id > 0){{route('post.update', [$post])}}@else{{route('post.create')}}@endif" method="post">
            @if($post->id > 0)@method('put')@endif

            @csrf

            <div class="row align-items-stretch">
                <div class="col-md-8 col-lg-9">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="title" id="title-label" class="sr-only">Title</label>
                            <input class="form-control form-control-lg" id="title" name="title" aria-describedby="title-label" placeholder="Enter A Title" value="{{old('title', $post->title)}}"/>
                            @error('title')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    @if ($post->id > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="slug" id="slug-label" class="sr-only">Slug</label>
                                <input class="form-control form-control-lg" id="slug" name="slug" aria-describedby="slug-label" placeholder="Post Slug" value="{{old('slug', $post->slug)}}"/>
                                <small>This is the url-friendly name of the post, it should only contain letters,
                                    numbers, and dashes</small>
                                @error('slug')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="excerpt" id="excerpt-label" class="sr-only">Excerpt</label>
                            <textarea class="form-control form-control-lg" id="excerpt" name="excerpt" aria-describedby="excerpt-label" placeholder="Enter a short description of this post">{{old('excerpt', $post->excerpt)}}</textarea>
                            <small>This is the short description of the post, if left blank it is generated using the
                                first paragraph of the body, this description is shown on listing pages</small>
                            @error('excerpt')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <input type="hidden" name="body" value="{{old('body', $post->body)}}"/>
                            @error('body')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                            <label for="bodycontent" id="bodycontent-label" class="sr-only">Content</label>
                            <rich-editor id="bodycontent" name="body" content="{{old('body', $post->body)}}"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 bg-light py-3">
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </div>
                    </div>
                    @if ($post->id > 0 && Gate::allows('view', $post))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('post.show', $post)}}" class="btn btn-secondary w-100">View</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </form>

    </div>
@endsection
