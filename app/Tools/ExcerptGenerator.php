<?php


namespace App\Tools;


use Illuminate\Support\Str;

class ExcerptGenerator
{
    public function __construct(string $body)
    {
        $this->excerpt = strip_tags(Str::before($body, "\r\n"));
    }

    public function get()
    {
        return $this->excerpt;
    }
}
