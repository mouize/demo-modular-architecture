<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_list_comments()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        Comment::factory()->count(3)->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson('/api/comments');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'post_id' => $post->id,
            'content' => 'This is a test comment.',
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/comments', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['content' => 'This is a test comment.']);
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_can_update_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $data = ['content' => 'Updated Comment'];

        $response = $this->actingAs($this->user)->putJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'Updated Comment']);
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_can_delete_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
