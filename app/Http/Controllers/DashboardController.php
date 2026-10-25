<?php

namespace App\Http\Controllers;

use App\Models\ContentTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'credits' => $user->credits,
            'total_documents' => $user->documents()->count(),
            'total_tokens' => (int) $user->documents()->sum('tokens_used'),
            'favorites_count' => $user->documents()->where('is_favorite', true)->count(),
        ];

        $recentDocuments = $user->documents()
            ->with('template')
            ->latest()
            ->take(5)
            ->get();

        $templates = ContentTemplate::where('is_active', true)->get();

        return view('dashboard', compact('stats', 'recentDocuments', 'templates'));
    }
}
