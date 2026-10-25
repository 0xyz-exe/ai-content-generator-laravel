<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Content Templates') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Custom prompt card banner -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white flex flex-col sm:flex-row justify-between items-center shadow-md">
                <div class="space-y-1 mb-4 sm:mb-0">
                    <h3 class="text-xl font-bold">Have a custom prompt in mind?</h3>
                    <p class="text-indigo-100 text-sm">Generate anything without template constraints using our multi-model AI studio.</p>
                </div>
                <a href="{{ route('generator.create') }}" class="px-5 py-2.5 bg-white text-indigo-700 font-semibold rounded-xl text-sm shadow hover:bg-indigo-50 transition">
                    Open Freeform Studio &rarr;
                </a>
            </div>

            <!-- Templates List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($templates as $tmpl)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                    {{ $tmpl->category }}
                                </span>
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">
                                    {{ $tmpl->cost_credits }} {{ Str::plural('Credit', $tmpl->cost_credits) }}
                                </span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $tmpl->name }}</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $tmpl->description }}</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('generator.template', $tmpl->slug) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition">
                                Generate with this &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
