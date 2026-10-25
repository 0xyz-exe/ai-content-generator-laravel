<?php

namespace Tests\Feature;

use App\Models\ContentTemplate;
use App\Models\Document;
use App\Models\User;
use Database\Seeders\ContentTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiContentGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContentTemplateSeeder::class);
    }

    public function test_user_can_access_dashboard_with_stats(): void
    {
        $user = User::factory()->create(['credits' => 45]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('AI Studio Dashboard');
        $response->assertSee('45');
    }

    public function test_user_can_view_templates_and_generator(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/generator');
        $response->assertStatus(200);
        $response->assertSee('Blog Post Generator');

        $responseTemplate = $this->actingAs($user)->get('/generator/template/blog-post-generator');
        $responseTemplate->assertStatus(200);
        $responseTemplate->assertSee('Generate Now');
    }

    public function test_user_can_generate_content_with_sufficient_credits(): void
    {
        $user = User::factory()->create(['credits' => 10]);
        $template = ContentTemplate::where('slug', 'blog-post-generator')->first();

        $response = $this->actingAs($user)->post('/generator/generate', [
            'template_id' => $template->id,
            'title' => 'Scaling Systems 2026',
            'prompt' => 'How to scale microservices reliably',
            'tone' => 'professional',
            'model' => 'gpt-4o',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(8, $user->fresh()->credits); // 10 - 2

        $this->assertDatabaseHas('documents', [
            'user_id' => $user->id,
            'title' => 'Scaling Systems 2026',
            'model' => 'gpt-4o',
            'credits_used' => 2,
        ]);

        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $user->id,
            'amount' => -2,
            'type' => 'usage',
        ]);
    }

    public function test_user_cannot_generate_content_without_credits(): void
    {
        $user = User::factory()->create(['credits' => 0]);
        $template = ContentTemplate::where('slug', 'blog-post-generator')->first();

        $response = $this->actingAs($user)->from('/generator/create')->post('/generator/generate', [
            'template_id' => $template->id,
            'prompt' => 'Test prompt with zero credits',
            'tone' => 'casual',
            'model' => 'gpt-4o',
        ]);

        $response->assertRedirect('/generator/create');
        $response->assertSessionHas('error');
        $this->assertEquals(0, $user->fresh()->credits);
        $this->assertDatabaseCount('documents', 0);
    }

    public function test_user_can_view_own_document_but_not_others(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $docA = Document::factory()->create([
            'user_id' => $userA->id,
            'title' => 'User A Secret Project',
        ]);

        // User A can view
        $responseA = $this->actingAs($userA)->get("/documents/{$docA->id}");
        $responseA->assertStatus(200);
        $responseA->assertSee('User A Secret Project');

        // User B cannot view (403 forbidden)
        $responseB = $this->actingAs($userB)->get("/documents/{$docA->id}");
        $responseB->assertStatus(403);
    }

    public function test_user_can_update_own_document(): void
    {
        $user = User::factory()->create();
        $doc = Document::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'generated_content' => 'Initial content',
        ]);

        $response = $this->actingAs($user)->put("/documents/{$doc->id}", [
            'title' => 'Revised Title',
            'generated_content' => 'Updated content with edits',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documents', [
            'id' => $doc->id,
            'title' => 'Revised Title',
            'generated_content' => 'Updated content with edits',
        ]);
    }

    public function test_user_can_delete_own_document(): void
    {
        $user = User::factory()->create();
        $doc = Document::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete("/documents/{$doc->id}");
        $response->assertRedirect('/documents');

        $this->assertDatabaseMissing('documents', [
            'id' => $doc->id,
        ]);
    }

    public function test_user_can_toggle_favorite_status(): void
    {
        $user = User::factory()->create();
        $doc = Document::factory()->create([
            'user_id' => $user->id,
            'is_favorite' => false,
        ]);

        $response = $this->actingAs($user)->post("/documents/{$doc->id}/favorite");
        $response->assertSessionHas('success');
        $this->assertTrue($doc->fresh()->is_favorite);

        // Toggle back
        $this->actingAs($user)->post("/documents/{$doc->id}/favorite");
        $this->assertFalse($doc->fresh()->is_favorite);
    }

    public function test_user_can_topup_wallet_credits(): void
    {
        $user = User::factory()->create(['credits' => 10]);

        $response = $this->actingAs($user)->post('/wallet/topup', [
            'pack' => 'starter_50',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(60, $user->fresh()->credits);

        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $user->id,
            'amount' => 50,
            'type' => 'topup',
        ]);
    }
}
