<?php

namespace App\Http\Controllers;

use App\Models\ContentTemplate;
use App\Models\CreditTransaction;
use App\Models\Document;
use App\Services\AiGenerationEngine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GeneratorController extends Controller
{
    public function index(): View
    {
        $templates = ContentTemplate::where('is_active', true)->get();
        return view('generator.index', compact('templates'));
    }

    public function create(): View
    {
        $templates = ContentTemplate::where('is_active', true)->get();
        $template = null;
        return view('generator.create', compact('template', 'templates'));
    }

    public function showTemplate(ContentTemplate $template): View
    {
        $templates = ContentTemplate::where('is_active', true)->get();
        return view('generator.create', compact('template', 'templates'));
    }

    public function generate(Request $request, AiGenerationEngine $engine): RedirectResponse
    {
        $validated = $request->validate([
            'template_id' => 'nullable|exists:content_templates,id',
            'title' => 'nullable|string|max:255',
            'prompt' => 'required|string|min:3|max:2000',
            'tone' => 'required|string|in:professional,casual,enthusiastic,urgent,informative',
            'model' => 'required|string|in:gpt-4o,claude-3-5-sonnet,mistral-large',
        ]);

        $user = $request->user();
        $template = !empty($validated['template_id'])
            ? ContentTemplate::findOrFail($validated['template_id'])
            : null;

        $cost = $template ? $template->cost_credits : 1;

        if (!$user->hasCredits($cost)) {
            return back()->withInput()->with('error', "Insufficient credits ({$user->credits} available, {$cost} required). Please top up your wallet.");
        }

        $result = DB::transaction(function () use ($user, $template, $validated, $cost, $engine) {
            // Deduct credits
            $user->decrement('credits', $cost);

            // Record transaction
            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => -$cost,
                'type' => 'usage',
                'description' => 'Generated content: ' . ($template ? $template->name : 'Custom Prompt'),
            ]);

            // Call AI Generation Engine
            $aiOutput = $engine->generate($template, $validated['prompt'], $validated['tone'], $validated['model']);

            $title = !empty($validated['title'])
                ? $validated['title']
                : ($template ? $template->name . ' - ' . substr($validated['prompt'], 0, 30) : substr($validated['prompt'], 0, 40));

            return Document::create([
                'user_id' => $user->id,
                'content_template_id' => $template?->id,
                'title' => $title,
                'prompt' => $validated['prompt'],
                'tone' => $validated['tone'],
                'model' => $validated['model'],
                'generated_content' => $aiOutput['content'],
                'tokens_used' => $aiOutput['tokens_used'],
                'credits_used' => $cost,
                'is_favorite' => false,
            ]);
        });

        return redirect()->route('documents.show', $result)->with('success', 'Content generated successfully!');
    }
}
