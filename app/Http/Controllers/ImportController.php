<?php

namespace App\Http\Controllers;

use App\Facades\Ofx;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImportController extends Controller
{
    public function importOfx(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $ofx = Ofx::load($request->file('file')->get());

        $account = $ofx->account();

        $existingAccount = $user->defaultProject->accounts()->where('number', $account['number'])->first();

        if (! $existingAccount) {
            $account = $user->defaultProject->accounts()->create($account);
        } else {
            $account = $existingAccount;
        }

        $transactions = $ofx->transactions();

        $newTransactions = collect($transactions)->filter(function ($transactionData) use ($account) {
            return !$account->transactions()->where([
                'fitid' => $transactionData['fitid'],
                'date_posted' => $transactionData['date_posted'],
                'amount' => $transactionData['amount'],
            ])->exists();
        })->toArray();

        $count = $account->transactions()->createMany($newTransactions)->count();

        return response()->json(['count' => $count ], Response::HTTP_CREATED);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv|max:10240',
        ]);
    }
}
