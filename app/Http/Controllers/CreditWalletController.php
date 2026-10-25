<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreditWalletController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $transactions = $user->creditTransactions()->latest()->paginate(15);

        return view('wallet.index', compact('transactions'));
    }

    public function topup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pack' => 'required|string|in:starter_50,pro_200,enterprise_1000',
        ]);

        $packs = [
            'starter_50' => ['credits' => 50, 'cost_cents' => 900, 'label' => '50 Credits Pack'],
            'pro_200' => ['credits' => 200, 'cost_cents' => 2900, 'label' => '200 Credits Pack'],
            'enterprise_1000' => ['credits' => 1000, 'cost_cents' => 9900, 'label' => '1,000 Credits Pack'],
        ];

        $selected = $packs[$validated['pack']];
        $user = $request->user();

        DB::transaction(function () use ($user, $selected) {
            $user->increment('credits', $selected['credits']);

            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $selected['credits'],
                'type' => 'topup',
                'description' => 'Wallet recharge: ' . $selected['label'] . ' ($' . number_format($selected['cost_cents'] / 100, 2) . ')',
            ]);
        });

        return back()->with('success', "Added {$selected['credits']} credits to your wallet!");
    }
}
