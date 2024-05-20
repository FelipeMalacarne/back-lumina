<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(AccountResource::make($request->user()->defaultProject->accounts));
    }
}
