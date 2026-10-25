<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Credit Wallet & Recharging') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Balance Hero -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-8 text-white shadow-md flex flex-col md:flex-row justify-between items-center">
                <div>
                    <span class="text-amber-100 text-sm font-semibold uppercase tracking-wider">Available Balance</span>
                    <div class="text-4xl font-extrabold mt-1">{{ Auth::user()->credits }} Credits</div>
                    <p class="text-amber-100 text-xs mt-2">Active Plan: <span class="uppercase font-bold tracking-wide">{{ Auth::user()->plan }}</span></p>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    <span class="text-xs text-amber-100">Standard cost is 1 to 2 credits per generation.</span>
                </div>
            </div>

            <!-- Top Up Credit Packages -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Recharge Credit Packs</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Starter -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:border-indigo-300 transition">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Starter</span>
                            <div class="mt-2 flex items-baseline">
                                <span class="text-3xl font-extrabold text-gray-900">$9</span>
                                <span class="text-xs text-gray-400 ml-1">one-time</span>
                            </div>
                            <div class="mt-4 text-sm font-bold text-indigo-600">50 Credits</div>
                            <ul class="mt-3 text-xs text-gray-500 space-y-2">
                                <li>• Approx 30-50 articles & captions</li>
                                <li>• Standard generation speed</li>
                                <li>• Never expires</li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('wallet.topup') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="pack" value="starter_50">
                            <button type="submit" class="w-full py-2 px-4 bg-gray-50 hover:bg-gray-100 text-gray-800 font-semibold rounded-lg text-sm border border-gray-200 transition">
                                Purchase 50 Credits
                            </button>
                        </form>
                    </div>

                    <!-- Pro (Featured) -->
                    <div class="bg-white rounded-xl shadow-md border-2 border-indigo-600 p-6 flex flex-col justify-between relative">
                        <span class="absolute -top-3 right-4 px-3 py-0.5 bg-indigo-600 text-white rounded-full text-xs font-bold uppercase tracking-wider">Most Popular</span>
                        <div>
                            <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Professional</span>
                            <div class="mt-2 flex items-baseline">
                                <span class="text-3xl font-extrabold text-gray-900">$29</span>
                                <span class="text-xs text-gray-400 ml-1">one-time</span>
                            </div>
                            <div class="mt-4 text-sm font-bold text-indigo-600">200 Credits</div>
                            <ul class="mt-3 text-xs text-gray-500 space-y-2">
                                <li>• Approx 150-200 articles & assets</li>
                                <li>• Priority generation queue</li>
                                <li>• All AI models unlocked</li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('wallet.topup') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="pack" value="pro_200">
                            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow transition">
                                Purchase 200 Credits
                            </button>
                        </form>
                    </div>

                    <!-- Enterprise -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:border-indigo-300 transition">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">High Volume</span>
                            <div class="mt-2 flex items-baseline">
                                <span class="text-3xl font-extrabold text-gray-900">$99</span>
                                <span class="text-xs text-gray-400 ml-1">one-time</span>
                            </div>
                            <div class="mt-4 text-sm font-bold text-indigo-600">1,000 Credits</div>
                            <ul class="mt-3 text-xs text-gray-500 space-y-2">
                                <li>• Maximum bulk discount</li>
                                <li>• Dedicated processing priority</li>
                                <li>• Highest output token caps</li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('wallet.topup') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="pack" value="enterprise_1000">
                            <button type="submit" class="w-full py-2 px-4 bg-gray-50 hover:bg-gray-100 text-gray-800 font-semibold rounded-lg text-sm border border-gray-200 transition">
                                Purchase 1,000 Credits
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ledger Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-lg">Transaction History</h3>
                </div>
                @if($transactions->isEmpty())
                    <div class="p-8 text-center text-gray-500 text-sm">
                        No transactions recorded yet.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($transactions as $tx)
                            <div class="p-4 flex items-center justify-between text-sm">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $tx->description }}</div>
                                    <div class="text-xs text-gray-400">{{ $tx->created_at->format('M d, Y H:i:s') }}</div>
                                </div>
                                <div class="font-mono font-bold {{ $tx->amount > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }} Credits
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
