<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Generated Documents') }}
            </h2>
            <a href="{{ route('generator.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                + Create New
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
                <form method="GET" action="{{ route('documents.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search documents..." class="w-full sm:w-64 text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

                    <select name="template_id" class="text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Templates</option>
                        @foreach($templates as $tmpl)
                            <option value="{{ $tmpl->id }}" @selected(request('template_id') == $tmpl->id)>{{ $tmpl->name }}</option>
                        @endforeach
                    </select>

                    <label class="inline-flex items-center space-x-2 text-sm text-gray-700 cursor-pointer self-center">
                        <input type="checkbox" name="favorite_only" value="1" @checked(request('favorite_only')) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span>Starred only</span>
                    </label>

                    <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">Filter</button>
                    @if(request()->hasAny(['search', 'template_id', 'favorite_only']))
                        <a href="{{ route('documents.index') }}" class="px-3 py-2 text-gray-500 hover:text-gray-700 text-sm self-center">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Documents Grid -->
            @if($documents->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
                    <p class="text-gray-500 text-sm">No documents found matching your filter.</p>
                    <a href="{{ route('generator.create') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                        Start Generating
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documents as $doc)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ $doc->template ? $doc->template->name : 'Custom Prompt' }}
                                    </span>
                                    <form method="POST" action="{{ route('documents.favorite', $doc) }}">
                                        @csrf
                                        <button type="submit" class="text-lg {{ $doc->is_favorite ? 'text-amber-500' : 'text-gray-300 hover:text-amber-400' }}">
                                            ★
                                        </button>
                                    </form>
                                </div>
                                <h3 class="font-bold text-gray-900 text-base line-clamp-1">
                                    <a href="{{ route('documents.show', $doc) }}" class="hover:text-indigo-600">
                                        {{ $doc->title }}
                                    </a>
                                </h3>
                                <p class="mt-2 text-xs text-gray-500 line-clamp-3">
                                    {{ Str::limit(strip_tags($doc->generated_content), 120) }}
                                </p>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $doc->tokens_used }} tokens</span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('documents.show', $doc) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">View</a>
                                    <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
