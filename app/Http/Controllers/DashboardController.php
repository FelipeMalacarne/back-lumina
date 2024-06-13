<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function totalBalance(Request $request)
    {
        $totalBalance = $request->user()->defaultProject->accounts()->sum('balance');
        $lastMonthTransactionsSum = $request->user()->defaultProject->transactions()
            ->whereBetween('date_posted', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');

        $percentageChange = round($lastMonthTransactionsSum > 0 ? (($totalBalance - $lastMonthTransactionsSum) / $lastMonthTransactionsSum) * 100 : 0, 2);

        return response()->json([
            'total_balance' => Number::currency($totalBalance / 100, 'BRL'),
            'percentage_change' => $percentageChange . '%'
        ]);
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
            'percentage_change' => $percentageChange,
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
            'percentage_change' => $percentageChange,
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

    public function inOutOnYear(Request $request)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthIncome = $request->user()->defaultProject->transactions()
                ->where('amount', '>', 0)
                ->whereYear('date_posted', now()->year)
                ->whereMonth('date_posted', $i)
                ->sum('amount');

            $monthExpense = abs($request->user()->defaultProject->transactions()
                ->where('amount', '<', 0)
                ->whereYear('date_posted', now()->year)
                ->whereMonth('date_posted', $i)
                ->sum('amount'));

            $monthIncome = round($monthIncome / 100, 2);
            $monthExpense = round($monthExpense / 100, 2);

            $data[] = [
                'month' => $months[$i - 1],
                'income' => $monthIncome,
                'expense' => $monthExpense,
                'profit' => $monthIncome - $monthExpense,
            ];
        }

        return $data;
    }
}
