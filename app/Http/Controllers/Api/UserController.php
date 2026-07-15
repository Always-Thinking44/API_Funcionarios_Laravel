<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->get();

        return response()->json([
            'status' => 'true',
            'users' => $users,
        ], 200);
    }

}
