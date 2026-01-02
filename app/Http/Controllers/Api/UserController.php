<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users (admin-only)
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return response()->json(User::all());
    }

    // Get user details
    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);
        return response()->json($user);
    }

    // Get orders of a user
    public function orders($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);
        return response()->json($user->orders);
    }
}
