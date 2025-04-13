<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_list_posts()
    {
        Post::factory()
            ->count(3)
            ->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_post()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Post']);
        $this->assertDatabaseHas('posts', $data);
    }

    public function test_can_update_post()
    {
        $post = Post::factory()
            ->create(['user_id' => $this->user->id]);

        $data = ['title' => 'Updated Title'];

        $response = $this->actingAs($this->user)->putJson("/api/posts/{$post->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);
        $this->assertDatabaseHas('posts', $data);
    }

    public function test_can_delete_post()
    {
        $post = Post::factory()
            ->for(User::factory())
            ->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
