<?php

namespace App\Http\Controllers;

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
}
