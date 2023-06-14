<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //getuser
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    //post usuarios
    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'data' => $user,
        ]);
    }
}
