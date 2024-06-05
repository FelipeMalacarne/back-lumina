<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $request->user()->defaultProject->transactions()
            ->when($request->query('type') === 'credit', function ($query) {
                $query->where('amount', '>', 0);
            })
            ->when($request->query('type') === 'debit', function ($query) {
                $query->where('amount', '<', 0);
            })
            ->orderBy('date_posted', 'desc')
            ->paginate();

        return TransactionResource::collection($transactions);
    }

    public function store(Request $request)
    {
        $transaction = $request->user()->transactions()->create($request->all());

        return response()->json($transaction, Response::HTTP_CREATED);
    }

    public function show(Request $request, string $transaction)
    {
        return response()->json($request->user()->transactions()->findOrFail($transaction), Response::HTTP_OK);
    }

    public function update(Request $request, string $transaction)
    {
        $transaction = $request->user()->transactions()->findOrFail($transaction);
        $transaction->update($request->all());

        return response()->json($transaction, Response::HTTP_OK);
    }

    public function destroy(Request $request, string $transaction)
    {
        $transaction = $request->user()->transactions()->findOrFail($transaction);
        $transaction->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
