<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        logger($request->user()->defaultProject->accounts);
        return response()->json(AccountResource::collection($request->user()->defaultProject->accounts));
    }
}
