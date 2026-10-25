<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $document->title }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('documents.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                    &larr; All Documents
                </a>
                <form method="POST" action="{{ route('documents.favorite', $document) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-semibold {{ $document->is_favorite ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        {{ $document->is_favorite ? '★ Starred' : '☆ Star' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Permanently delete this document?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Main Content / Editor -->
                <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <form method="POST" action="{{ route('documents.update', $document) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block text-xs font-semibold uppercase text-gray-500 tracking-wider mb-1">Document Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $document->title) }}" class="w-full text-base font-bold text-gray-900 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="generated_content" class="block text-xs font-semibold uppercase text-gray-500 tracking-wider">Generated Text (Markdown)</label>
                                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('generated_content').value); alert('Copied to clipboard!');" class="text-xs text-indigo-600 hover:underline font-medium">
                                    Copy Text
                                </button>
                            </div>
                            <textarea id="generated_content" name="generated_content" rows="18" class="w-full font-mono text-sm leading-relaxed border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('generated_content', $document->generated_content) }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm shadow transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Document Metadata Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
                        <h4 class="font-bold text-gray-900 text-sm border-b pb-2">Details</h4>
                        <div class="text-xs space-y-3">
                            <div>
                                <span class="text-gray-400 block">Template:</span>
                                <span class="font-semibold text-gray-800">{{ $document->template ? $document->template->name : 'Custom Prompt' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Model Engine:</span>
                                <span class="font-semibold text-indigo-600">{{ $document->model }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Tone:</span>
                                <span class="font-semibold text-gray-800">{{ ucfirst($document->tone) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Tokens:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($document->tokens_used) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Credits Spent:</span>
                                <span class="font-semibold text-amber-600">{{ $document->credits_used }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Created:</span>
                                <span class="text-gray-600">{{ $document->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-2">
                        <span class="text-xs font-semibold uppercase text-gray-500">Original Prompt</span>
                        <p class="text-xs text-gray-700 italic bg-white p-3 rounded border border-gray-200">{{ $document->prompt }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
