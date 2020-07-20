<?php


namespace App\Tools;


use App\Post;
use Illuminate\Support\Str;

class SlugGenerator
{
    public function __construct(string $title)
    {
        $this->slug = Str::slug($title);
        $baseTitle = $this->slug;
        $appendage = 1;
        while (Post::where('slug', $this->slug)
                ->count() > 0) {
            $appendage++;
            $this->slug = $baseTitle . '-' . $appendage;
        }
    }

    public function get()
    {
        return $this->slug;
    }
}
