<?php

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_can_view_post_list()
    {
        $this->get('/posts')
            ->assertStatus(200);
    }

    public function test_it_can_view_published_post()
    {
        $post = factory(Post::class)->create(['published_at' => now()]);
        $this->get(route('post.show', $post->slug))
            ->assertStatus(200);
    }

    public function test_it_cannot_view_unpublished_post()
    {
        $post = factory(Post::class)->create(['published_at' => null]);
        $this->get(route('post.show', $post->slug))
            ->assertStatus(404);
    }

    public function test_it_can_view_unpublished_post_as_author()
    {
        $post = factory(Post::class)->create(['published_at' => null]);
        $author = $post->author;
        $this->actingAs($author)
            ->get(route('post.show', $post->slug))
            ->assertStatus(200);
    }

    public function test_it_cannot_view_create_unauthed()
    {
        $this->get(route('post.new'))
            ->assertStatus(403);
    }

    public function test_it_can_view_create_authed()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->get(route('post.new'))
            ->assertStatus(200);
    }
    public function test_it_cannot_update_other_posts()
    {
        $post = factory(Post::class)->Create();
        $user = factory(User::class)->Create();
        $this->actingAs($user)
            ->get(route('post.edit', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_update_own_posts()
    {
        $post = factory(Post::class)->Create();
        $this->actingAs($post->author)
            ->get(route('post.edit', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_delete_other_posts()
    {
        $post = factory(Post::class)->Create();
        $user = factory(User::class)->Create();
        $this->actingAs($user)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_delete_own_posts()
    {
        $post = factory(Post::class)->Create();
        $this->actingAs($post->author)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(200);
    }

}
