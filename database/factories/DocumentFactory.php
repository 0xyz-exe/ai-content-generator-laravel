<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content_template_id' => null,
            'title' => fake()->sentence(4),
            'prompt' => fake()->paragraph(),
            'tone' => 'professional',
            'model' => 'gpt-4o',
            'generated_content' => fake()->paragraphs(3, true),
            'tokens_used' => fake()->numberBetween(100, 500),
            'credits_used' => 1,
            'is_favorite' => false,
        ];
    }
}
