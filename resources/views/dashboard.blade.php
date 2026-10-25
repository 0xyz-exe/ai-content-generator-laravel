<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Studio Dashboard') }}
            </h2>
            <a href="{{ route('generator.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Generation
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Credit Balance</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-extrabold text-amber-600">{{ $stats['credits'] }}</span>
                        <span class="ml-2 text-xs text-gray-500">available</span>
                    </div>
                    <a href="{{ route('wallet.index') }}" class="mt-3 inline-block text-xs font-semibold text-indigo-600 hover:text-indigo-800">Recharge wallet &rarr;</a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Documents</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-extrabold text-gray-900">{{ $stats['total_documents'] }}</span>
                        <span class="ml-2 text-xs text-gray-500">created</span>
                    </div>
                    <a href="{{ route('documents.index') }}" class="mt-3 inline-block text-xs font-semibold text-indigo-600 hover:text-indigo-800">Browse library &rarr;</a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tokens Generated</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-extrabold text-indigo-600">{{ number_format($stats['total_tokens']) }}</span>
                        <span class="ml-2 text-xs text-gray-500">tokens</span>
                    </div>
                    <span class="mt-3 block text-xs text-gray-400">Across GPT-4o & Claude</span>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Favorites</div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-extrabold text-rose-600">{{ $stats['favorites_count'] }}</span>
                        <span class="ml-2 text-xs text-gray-500">saved</span>
                    </div>
                    <a href="{{ route('documents.index', ['favorite_only' => 1]) }}" class="mt-3 inline-block text-xs font-semibold text-indigo-600 hover:text-indigo-800">View starred &rarr;</a>
                </div>
            </div>

            <!-- Templates Grid -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">AI Prompt Templates</h3>
                    <a href="{{ route('generator.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">All Templates &rarr;</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($templates as $tmpl)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-indigo-300 transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                        {{ $tmpl->category }}
                                    </span>
                                    <span class="text-xs font-medium text-amber-600">
                                        {{ $tmpl->cost_credits }} {{ Str::plural('credit', $tmpl->cost_credits) }}
                                    </span>
                                </div>
                                <h4 class="mt-3 font-semibold text-gray-900 text-base">{{ $tmpl->name }}</h4>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $tmpl->description }}</p>
                            </div>
                            <div class="mt-5">
                                <a href="{{ route('generator.template', $tmpl->slug) }}" class="w-full text-center block px-4 py-2 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 font-medium rounded-lg text-sm border border-gray-200 transition">
                                    Use Template
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-lg">Recent Content Generations</h3>
                    <a href="{{ route('documents.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
                @if($recentDocuments->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        <p class="text-sm">No documents generated yet.</p>
                        <a href="{{ route('generator.index') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-600 hover:underline">Start your first generation &rarr;</a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentDocuments as $doc)
                            <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('documents.show', $doc) }}" class="font-semibold text-gray-900 hover:text-indigo-600">
                                            {{ $doc->title }}
                                        </a>
                                        @if($doc->is_favorite)
                                            <span class="text-rose-500" title="Favorite">★</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center space-x-3">
                                        <span>Model: {{ $doc->model }}</span>
                                        <span>&bull;</span>
                                        <span>Tone: {{ ucfirst($doc->tone) }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $doc->tokens_used }} tokens</span>
                                        <span>&bull;</span>
                                        <span>{{ $doc->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('documents.show', $doc) }}" class="px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-md transition">
                                        Open
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
