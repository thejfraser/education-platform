<?php

namespace App\View\Components;

use App\Post;
use Illuminate\View\Component;

class ListPost extends Component
{
    public $post;

    /**
     * Create a new component instance.
     *
     * @param Post $post
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-post');
    }
}
