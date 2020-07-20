<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tools\ExcerptGenerator;
use App\Tools\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int|null $page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, ?int $page = 1)
    {
        $postsPerPage = config('ep.posts_per_page');
        $page = max($page, 1) - 1;
        $postCount = Post::published()
            ->count();
        $offset = $page * $postsPerPage;
        if ($offset > $postCount) {
            abort(404);
        }

        $posts = Post::published()
            ->latest('published_at')
            ->limit($postsPerPage)
            ->skip($offset)
            ->get();
        if ($request->isXmlHttpRequest()) {
            $posts->each(function ($post) {
                $post->href = route('post.show', [$post->slug]);

                return $post;
            });

            return $posts;
        }

        return view('post.index', [
            'maxPage' => ceil($postCount / $postsPerPage),
            'page' => $page + 1,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.form', ['post' => new Post()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = $this->getValidationRules();

        $data = $request->validate([
            'title' => $validationRules['title'],
            'body' => $validationRules['body'],
            'excerpt' => $validationRules['excerpt'],
        ]);

        if (is_null($data['excerpt'])) {
            $data['excerpt'] = (new ExcerptGenerator($data['body']))->get();
        }

        $data['slug'] = (new SlugGenerator($data['title']))->get();
        $data['author_id'] = Auth::user()->id;

        $post = Post::create($data);

        return redirect(route('post.edit', $post))->with('created', true);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (Gate::denies('view', $post)) {
            abort(404, 'Not Found');
        }

        return view('post.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.form', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validationRules = $this->getValidationRules();

        $data = $request->validate([
            'title' => $validationRules['title'],
            'body' => $validationRules['body'],
            'excerpt' => $validationRules['excerpt'],
            'slug' => [
                Rule::unique('posts')
                    ->ignore($post->id),
            ],
        ]);

        if (is_null($data['excerpt'])) {
            $data['excerpt'] = (new ExcerptGenerator($data['body']))->get();
        }

        if (is_null($data['slug'])) {
            $data['slug'] = (new SlugGenerator($data['title']))->get();
        }

        $post->update($data);

        return back()->with('updated', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }


    private function getValidationRules()
    {
        return [
            'title' => 'required|max:65535',
            'slug' => 'unique:posts',
            'excerpt' => 'max:65535',
            'body' => 'required',
        ];
    }
}
