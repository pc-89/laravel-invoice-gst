<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get all users who are currently waiting for approval
        $pendingUsers = User::where('role', 'user')->where('is_approved', false)->get();
        return view('admin.dashboard', compact('pendingUsers'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);

        return redirect()->back()->with('success', $user->name . ' has been approved successfully!');
    }
}
