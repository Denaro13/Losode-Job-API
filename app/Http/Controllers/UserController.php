<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class UserController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        /** @var \App\Models\User $user */
        $user->update(['avatar' => $path]);

        return response()->json([
            'message' => 'Avatar uploaded successfully',
            'avatar_url' => asset('storage/' . $path)
        ]);
    }

    public function getUserJobs()
    {
        $user = Auth::user();
        // $jobs = Job::where('user_id', $user->id)->paginate(10);
        $jobs = $user->jobs;

        return response()->json([
            'status' => 'success',
            'data' => $jobs
        ], 200);
    }

    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'data' => new UserResource($user)
        ], 200);
    }
}
