<?php

use App\Permission;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    private function newPermission($name): Permission
    {
        return Permission::firstOrCreate(['name' => $name, 'label' => $name]);
    }

    private function newRole(array $permissions): Role
    {
        array_walk($permissions, function(Permission &$permission){
            $permission = $permission->id;
        });
        return tap(Role::firstOrCreate(['name' => 'test', 'label' => 'test']), function (Role $role) use ($permissions) {
            $role->permissions()
                ->sync($permissions);
        });
    }

    private function newUnpublishedPost(): Post
    {
        return factory(Post::class)->create(['published_at' => null]);
    }

    private function newPublishedPost(): Post
    {
        return factory(Post::class)->create(['published_at' => now()]);
    }

    //VIEW
    public function test_it_can_view_post_list()
    {
        $this->get('/posts')
            ->assertStatus(200);
    }

    public function test_it_can_view_published_post_unauthed()
    {
        $this->get(route('post.show', $this->newPublishedPost()->slug))
            ->assertStatus(200);
    }

    public function test_it_cannot_view_unpublished_post_unauthed()
    {
        $this->get(route('post.show', $this->newUnpublishedPost()->slug))
            ->assertStatus(404);
    }

    public function test_it_can_view_own_unpublished_posts()
    {
        $post = $this->newUnpublishedPost();
        $this->actingAs($post->author)
            ->get(route('post.show', $post->slug))
            ->assertStatus(200);
    }

    public function test_it_cannot_view_other_unpublished_posts_without_permission()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->get(route('post.show', $this->newUnpublishedPost()->slug))
            ->assertStatus(404);
    }

    public function test_it_can_view_other_unpublished_posts_with_permission()
    {
        $role = $this->newRole([$this->newPermission('edit-any-posts')]);
        $user = Factory(User::class)->create(['role_id' => $role->id]);
        $this->actingAs($user)
            ->get(route('post.show', $this->newUnpublishedPost()->slug))
            ->assertStatus(200);
    }

    //EDIT

    public function test_it_can_edit_own_unpublished_posts()
    {
        $post = $this->newUnpublishedPost();
        $this->actingAs($post->author)
            ->get(route('post.edit', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_edit_own_published_posts()
    {
        $post = $this->newPublishedPost();
        $this->actingAs($post->author)
            ->get(route('post.edit', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_edit_own_published_posts_with_permission()
    {
        $post = $this->newPublishedPost();
        $user = $post->author;
        $role = $this->newRole([$this->newPermission('including-published-posts')]);
        $user->role_id = $role->id;
        $user->save();

        $this->actingAs($user)
            ->get(route('post.edit', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_edit_other_unpublished_posts()
    {
        $user = factory(User::class)->Create();
        $this->actingAs($user)
            ->get(route('post.edit', $this->newUnpublishedPost()->id))
            ->assertStatus(403);
    }

    public function test_it_can_edit_other_unpublished_posts_with_permission()
    {
        $role = $this->newRole([$this->newPermission('edit-any-posts')]);
        $user = factory(User::class)->Create(['role_id' => $role->id]);

        $this->actingAs($user)
            ->get(route('post.edit', $this->newUnpublishedPost()->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_edit_other_published_posts()
    {
        $user = factory(User::class)->Create();
        $post = $this->newPublishedPost();
        $this->actingAs($user)
            ->get(route('post.edit', $post->id))
            ->assertStatus(403);

        $role = $this->newRole([$this->newPermission('edit-any-posts')]);
        $user->role_id = $role;
        $user->save();

        $this->actingAs($user)
            ->get(route('post.edit', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_edit_other_published_posts_with_permission()
    {
        $post = $this->newPublishedPost();
        $role = $this->newRole([$this->newPermission('edit-any-posts'), $this->newPermission('including-published-posts')]);
        $user = factory(User::class)->Create(['role_id' => $role->id]);

        $this->actingAs($user)
            ->get(route('post.edit', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_create_without_permission()
    {
        $this->get(route('post.new'))
            ->assertStatus(403);
    }

    public function test_it_can_create_with_permission()
    {
        $role = $this->newRole([$this->newPermission('edit-own-posts')]);
        $user = factory(User::class)->create(['role_id' => $role->id]);
        $this->actingAs($user)
            ->get(route('post.new'))
            ->assertStatus(200);
    }

    // DELETING
    public function test_it_can_delete_own_unpublished_posts()
    {
        $post = $this->newUnpublishedPost();
        $this->actingAs($post->author)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_delete_other_unpublished_posts_without_permission()
    {
        $post = $this->newUnpublishedPost();
        $user = factory(User::class)->Create();
        $this->actingAs($user)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_delete_other_unpublished_posts_with_permission()
    {
        $role = $this->newRole([$this->newPermission('delete-any-posts')]);
        $user = Factory(User::class)->create(['role_id' => $role->id]);
        $this->actingAs($user)
            ->delete(route('post.delete', $this->newUnpublishedPost()->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_delete_own_published_posts_without_permission()
    {
        $post = $this->newPublishedPost();
        $this->actingAs($post->author)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_delete_own_published_posts_with_permission()
    {
        $role = $this->newRole([$this->newPermission('including-published-posts')]);
        $post = $this->newPublishedPost();
        $this->actingAs(tap($post->author, function (User $user) use ($role) {
            $user->role_id = $role->id;
            $user->save();
        }))
            ->delete(route('post.delete', $post->id))
            ->assertStatus(200);
    }

    public function test_it_cannot_delete_other_published_posts_without_permission()
    {
        $user = factory(User::class)->create();
        $post = $this->newPublishedPost();
        $this->actingAs($user)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(403);

        $role = $this->newRole([$this->newPermission('delete-any-posts')]);
        $user->role_id = $role->id;
        $user->save();

        $this->actingAs($user)
            ->delete(route('post.delete', $post->id))
            ->assertStatus(403);
    }

    public function test_it_can_delete_other_published_posts_with_permission()
    {
        $role = $this->newRole([$this->newPermission('delete-any-posts'), $this->newPermission('including-published-posts')]);
        $user = factory(User::class)->create(['role_id' => $role->id]);

        $this->actingAs($user)
            ->delete(route('post.delete', $this->newPublishedPost()->id))
            ->assertStatus(200);
    }
}
