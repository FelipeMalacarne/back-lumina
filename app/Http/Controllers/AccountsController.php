<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\CreateAccount;
use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $accounts = $request->user()->defaultProject->accounts;

        return response()->json(AccountResource::collection($accounts), Response::HTTP_OK);
    }

    public function show(Request $request, $id)
    {
        $account = $request->user()->defaultProject->accounts()->findOrFail($id);
        return response()->json(AccountResource::make($account), Response::HTTP_OK);
    }

    public function store(CreateAccount $request)
    {
        $account = $request->user()->defaultProject->accounts()->create($request->all());

        return response()->json(AccountResource::make($account), Response::HTTP_CREATED);
    }
}
