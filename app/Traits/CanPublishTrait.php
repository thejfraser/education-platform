<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait CanPublishTrait
{
    public function scopePublished(Builder $query)
    {
        $query->whereNotNull('published_at');
    }

    public function scopeNotPublished(Builder $query)
    {
        $query->whereNull('published_at');
    }
}
