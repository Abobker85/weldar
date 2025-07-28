<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Show form to create a new user
    public function create()
    {
        $users = User::all();
        return view('admin.users.create', compact('users'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:user,admin,qc', // Add role validation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Use role from request
        ]);

        return redirect()->route('admin.users.create')->with('success', 'User created successfully.');
    }

    // Show all users as datatable and allow reset password
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Reset password to default
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('12345678');
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Password reset to 12345678 for user: ' . $user->email);
    }
}
