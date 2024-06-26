<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(ProjectResource::collection($request->user()->projects), Response::HTTP_OK);
    }
}
