<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // Return json response
        return response()->json($users);
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('search');
        $users = User::where('name', 'LIKE', "%$search%")
            ->orWhere('username', 'LIKE', "%$search%")
            ->select('name', 'username')
            ->get();
        // Return json response
        return response()->json($users);
    }
}
