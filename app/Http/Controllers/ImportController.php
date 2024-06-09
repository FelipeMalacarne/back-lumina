<?php

namespace App\Http\Controllers;

use App\Facades\Ofx;
use App\Models\Transaction;
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

        if(!$existingAccount) {
            $account = $user->defaultProject->accounts()->create($account);
        } else {
            $account = $existingAccount;
        }

        $transactions = $ofx->transactions();

        $account->transactions()->createMany($transactions);


        return response()->json(null, Response::HTTP_CREATED);

    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv|max:10240',
        ]);
    }
}
