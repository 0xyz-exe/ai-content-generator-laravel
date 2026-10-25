<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $template ? $template->name : __('Custom AI Generation') }}
            </h2>
            <a href="{{ route('generator.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                &larr; Back to templates
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Form Column -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form method="POST" action="{{ route('generator.generate') }}" class="space-y-6">
                        @csrf
                        @if($template)
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                        @else
                            <div>
                                <label for="template_id" class="block text-sm font-medium text-gray-700 mb-1">Select Preset (Optional)</label>
                                <select id="template_id" name="template_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- None (Freeform AI Prompt) --</option>
                                    @foreach($templates as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->cost_credits }} cr)</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Document Title (Optional)</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Q4 Strategy Memo" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="prompt" class="block text-sm font-medium text-gray-700 mb-1">Your Prompt / Topic <span class="text-rose-500">*</span></label>
                            <textarea id="prompt" name="prompt" rows="5" required placeholder="Describe what you want the AI to write about..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('prompt') }}</textarea>
                            @error('prompt')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="tone" class="block text-sm font-medium text-gray-700 mb-1">Tone & Voice</label>
                                <select id="tone" name="tone" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="professional" @selected(old('tone') == 'professional')>Professional</option>
                                    <option value="casual" @selected(old('tone') == 'casual')>Casual & Friendly</option>
                                    <option value="enthusiastic" @selected(old('tone') == 'enthusiastic')>Enthusiastic & High-Energy</option>
                                    <option value="urgent" @selected(old('tone') == 'urgent')>Direct & Urgent</option>
                                    <option value="informative" @selected(old('tone') == 'informative')>Informative & Analytical</option>
                                </select>
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">AI Engine</label>
                                <select id="model" name="model" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="gpt-4o" @selected(old('model') == 'gpt-4o')>GPT-4o (Fast & Balanced)</option>
                                    <option value="claude-3-5-sonnet" @selected(old('model') == 'claude-3-5-sonnet')>Claude 3.5 Sonnet (Nuanced & Creative)</option>
                                    <option value="mistral-large" @selected(old('model') == 'mistral-large')>Mistral Large (Precise & Concise)</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                Cost: <span class="font-bold text-amber-600">{{ $template ? $template->cost_credits : 1 }}</span> Credits
                                (Balance: <span class="font-semibold text-gray-700">{{ Auth::user()->credits }}</span>)
                            </div>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Generate Now
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Sidebar Details -->
                <div class="space-y-6">
                    @if($template)
                        <div class="bg-indigo-50/50 rounded-xl border border-indigo-100 p-6 space-y-3">
                            <div class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">Active Preset</div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $template->description }}</p>
                            <div class="pt-3 border-t border-indigo-100/60 text-xs text-indigo-900 space-y-1">
                                <p class="font-semibold">System Guidance:</p>
                                <p class="italic text-gray-600">"{{ $template->system_prompt }}"</p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-4">
                        <h4 class="font-bold text-gray-900 text-sm">Best Practices</h4>
                        <ul class="text-xs text-gray-600 space-y-2 list-disc list-inside">
                            <li>Provide specific context, target audiences, or constraints.</li>
                            <li>Use bullet points in your prompt for structured content.</li>
                            <li>Generated drafts can be edited and saved in your library.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
