<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankResource;
use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
        return BankResource::collection(Bank::all());
    }

    public function show($id)
    {
        return BankResource::make(Bank::findOrFail($id));
    }
}
