<?php

namespace App\Http\Controllers;

use App\Models\ContentTemplate;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = $user->documents()->with('template')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('prompt', 'like', "%{$search}%")
                  ->orWhere('generated_content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('template_id')) {
            $query->where('content_template_id', $request->input('template_id'));
        }

        if ($request->boolean('favorite_only')) {
            $query->where('is_favorite', true);
        }

        $documents = $query->paginate(12)->withQueryString();
        $templates = ContentTemplate::where('is_active', true)->get();

        return view('documents.index', compact('documents', 'templates'));
    }

    public function show(Request $request, Document $document): View
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this document.');
        }

        return view('documents.show', compact('document'));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'generated_content' => 'required|string',
        ]);

        $document->update($validated);

        return back()->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted.');
    }

    public function toggleFavorite(Request $request, Document $document): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $document->update([
            'is_favorite' => !$document->is_favorite,
        ]);

        return back()->with('success', $document->is_favorite ? 'Added to favorites.' : 'Removed from favorites.');
    }
}
