<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DeleteAccountRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['register']);
        $this->middleware('throttle:10,1')->only(['register', 'updateProfile']);
    }

    // Register a new user
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => strip_tags($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => isset($validated['phone']) ? strip_tags($validated['phone']) : null,
        ]);

        // Send email verification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'User registered successfully. Please verify your email.',
            'user' => $user->only(['id', 'name', 'email'])
        ], 201);
    }

    // Retrieve current user's profile
    public function index()
    {
        $user = Auth::user()->load(['role', 'orders']);

        // Calculate user statistics
        $totalSpent = $user->orders()->sum('total_amount') ?? 0;
        $totalOrders = $user->orders()->count() ?? 0;
        $lastOrder = $user->orders()->latest()->first();

        return view('user.profile', compact('user', 'totalSpent', 'totalOrders', 'lastOrder'));
    }

    // Retrieve a user's profile (admin only or own profile)
    public function profile($userId)
    {
        $authUser = Auth::user();

        // Users can only view their own profile unless they're admin
        if ($authUser->id != $userId && !$authUser->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user->makeHidden(['remember_token'])
        ], 200);
    }

    // Update a user's profile
    public function updateProfile(UpdateProfileRequest $request, $userId)
    {
        $authUser = Auth::user();

        // Users can only update their own profile unless they're admin
        if ($authUser->id != $userId && !$authUser->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->validated();

        // Verify current password if changing password
        if (isset($data['password'])) {
            if (!isset($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 400);
            }
            $data['password'] = Hash::make($data['password']);
            unset($data['current_password']);
        }

        // Sanitize inputs
        if (isset($data['name'])) {
            $data['name'] = strip_tags($data['name']);
        }
        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }
        if (isset($data['phone'])) {
            $data['phone'] = strip_tags($data['phone']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()->makeHidden(['remember_token'])
        ], 200);
    }

    // Delete user account (admin only or own account with password confirmation)
    public function destroy(DeleteAccountRequest $request, $userId)
    {
        $authUser = Auth::user();

        // Users can only delete their own account unless they're admin
        if ($authUser->id != $userId && !$authUser->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Require password confirmation for self-deletion
        if ($authUser->id == $userId) {
            $validated = $request->validated();

            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'Password is incorrect'
                ], 400);
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'User account deleted successfully'
        ], 200);
    }
}
