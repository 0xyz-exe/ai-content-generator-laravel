<?php

namespace Database\Seeders;

use App\Models\ContentTemplate;
use Illuminate\Database\Seeder;

class ContentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Blog Post Generator',
                'slug' => 'blog-post-generator',
                'category' => 'Blogging',
                'description' => 'Generate high-ranking, engaging blog articles with outlines, headings, and takeaways.',
                'icon' => 'document-text',
                'system_prompt' => 'You are an expert content creator. Generate a comprehensive, engaging blog post with intro, subheadings, and a call to action.',
                'cost_credits' => 2,
            ],
            [
                'name' => 'Marketing Email Copy',
                'slug' => 'marketing-email-copy',
                'category' => 'Marketing',
                'description' => 'Draft high-converting newsletter or sales promotional email copy with catchy subject lines.',
                'icon' => 'envelope',
                'system_prompt' => 'You are a direct-response copywriter. Craft an email with subject line options, hook, value proposition, and crisp CTA.',
                'cost_credits' => 1,
            ],
            [
                'name' => 'Social Media Caption',
                'slug' => 'social-media-caption',
                'category' => 'Social',
                'description' => 'Create viral captions for LinkedIn, Twitter/X, and Instagram complete with hashtags.',
                'icon' => 'share',
                'system_prompt' => 'You are a viral social media strategist. Craft snappy, platform-optimized captions with strong hooks and relevant hashtags.',
                'cost_credits' => 1,
            ],
            [
                'name' => 'SEO Meta Descriptions',
                'slug' => 'seo-meta-descriptions',
                'category' => 'SEO',
                'description' => 'Write click-worthy meta titles and descriptions optimized within character limits.',
                'icon' => 'magnifying-glass',
                'system_prompt' => 'You are an SEO specialist. Generate 3 variants of title tags (<60 chars) and meta descriptions (<160 chars) including target keywords.',
                'cost_credits' => 1,
            ],
            [
                'name' => 'Code Explainer & Refactor',
                'slug' => 'code-explainer-refactor',
                'category' => 'Engineering',
                'description' => 'Deconstruct complex code snippets, explain algorithm steps, and provide clean refactors.',
                'icon' => 'code-bracket',
                'system_prompt' => 'You are a principal software architect. Explain what the provided code does, evaluate time/space complexity, and offer an optimized refactored version.',
                'cost_credits' => 2,
            ],
        ];

        foreach ($templates as $data) {
            ContentTemplate::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
