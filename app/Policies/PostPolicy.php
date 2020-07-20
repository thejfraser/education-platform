<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function view(?User $user, Post $post)
    {
        if (!is_null($post->published_at)) {
            return true;
        }

        if (is_null($user)) {
            return false;
        }

        if (is_null($post->published_at) && ($post->author->is($user) || $user->can('edit-any-posts'))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('edit-own-posts') || $user->can('edit-any-posts')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        if (!is_null($post->published_at) && ($post->author->is($user) || $user->can('edit-any-posts')) && $user->can('including-published-posts')) {
            return true;
        }

        if (is_null($post->published_at) && ($post->author->is($user) || $user->can('edit-any-posts'))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        if (!is_null($post->published_at) && ($post->author->is($user) || $user->can('delete-any-posts')) && $user->can('including-published-posts')) {
            return true;
        }

        if (is_null($post->published_at) && ($post->author->is($user) || $user->can('delete-any-posts'))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function restore(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function forceDelete(User $user, Post $post)
    {
        //
    }
}
