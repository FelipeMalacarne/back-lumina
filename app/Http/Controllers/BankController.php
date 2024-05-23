<?php

namespace App\Http\Controllers;

use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
        return Bank::paginate(60);
    }

    public function show($id)
    {
        return Bank::find($id);
    }
}
