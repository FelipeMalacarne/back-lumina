<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function totalBalance(Request $request)
    {
        $totalBalance = $request->user()->defaultProject->accounts()->sum('balance');
        return response()->json(['total_balance' => $totalBalance]);
    }

    public function monthlyIncome(Request $request)
    {
        $monthlyIncome = $request->user()->defaultProject->transactions()
            ->where('amount', '>', 0)
            ->whereBetween('date_posted', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $lastMonthIncome = $request->user()->defaultProject->transactions()
            ->where('amount', '>', 0)
            ->whereBetween('date_posted', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');

        $percentageChange = round($lastMonthIncome > 0 ? (($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0, 2);

        return response()->json([
            'monthly_income' => $monthlyIncome,
            'percentage_change' => $percentageChange
        ]);
    }

    public function monthlyExpense(Request $request)
    {
        $monthlyExpense = abs($request->user()->defaultProject->transactions()
            ->where('amount', '<', 0)
            ->whereBetween('date_posted', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount'));

        $lastMonthExpense = abs($request->user()->defaultProject->transactions()
            ->where('amount', '<', 0)
            ->whereBetween('date_posted', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount'));

        $percentageChange = round($lastMonthExpense > 0 ? (($monthlyExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : 0, 2);

        return response()->json([
            'monthly_expense' => $monthlyExpense,
            'percentage_change' => $percentageChange
        ]);
    }

    public function lastTransactions(Request $request)
    {
        $lastTransactions = $request->user()->defaultProject->transactions()
            ->with('account')
            ->whereBetween('date_posted', [now()->startOfMonth(), now()->endOfMonth()])
            ->orderBy('date_posted', 'desc')
            ->limit(5)
            ->get();

        $monthTransactionsCount = $request->user()->defaultProject->transactions()
            ->whereBetween('date_posted', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        return [
            'last_transactions' => TransactionResource::collection($lastTransactions),
            'count' => $monthTransactionsCount,
        ];
    }
}
