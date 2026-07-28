<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * List all users.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $query = User::where('school_id', $schoolId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->get()->map(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role,
            'is_suspended' => $u->is_suspended,
            'created_at' => $u->created_at->toISOString(),
            'last_login' => $u->updated_at->diffForHumans(),
        ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff,parent,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => Auth::user()->school_id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Toggle user suspension.
     */
    public function toggleSuspend(User $user)
    {
        abort_unless($user->school_id === Auth::user()->school_id, 403);
        abort_if($user->id === Auth::id(), 422, 'You cannot suspend yourself.');

        $user->update(['is_suspended' => !$user->is_suspended]);

        return redirect()->back()->with('success',
            $user->is_suspended ? 'User suspended.' : 'User reactivated.'
        );
    }
}
