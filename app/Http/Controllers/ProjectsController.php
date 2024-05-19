<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function userProjects(Request $request)
    {
        return response()->json($request->user()->projects);
    }
}
