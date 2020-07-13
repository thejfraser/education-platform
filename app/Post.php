<?php

namespace App;

use App\Traits\CanPublishTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/* @method static published() */

class Post extends Model
{
    protected $guarded = [];
    use SoftDeletes;
    use CanPublishTrait;

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
