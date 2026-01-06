<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $isFirstUser = User::count() === 0;

        $profilePhotoUrl = null;
        // 1. If user uploaded a file
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('profile-photos', 'public');
            // Store as relative path (e.g. "storage/profile-photos/abc.jpg")
            $profilePhotoUrl = 'storage/' . $path;
        } // 🧩 2. If not uploaded, use random avatar
        else {
            $profilePhotoUrl = User::randomAvatar();
        }


        $user = User::create([
            'first_name' => $request->string('first_name'),
            'last_name' => $request->string('last_name'),
            'email' => $request->string('email'),
            'password' => Hash::make($request->input('password')),
            'user_type' => $isFirstUser ? 0 : 1, // 0 = admin, 1 = user
            'avatar' => $profilePhotoUrl,
        ]);

        return redirect()
            ->route('page.show', ['name' => 'login'])
            ->with('success', 'Registration successful. Please log in.');
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'avatar' => ['nullable', 'image', 'max:2048'], // jpg/png/gif/webp
            'address' => ['nullable', 'string', 'max:255'],
            'phone_num' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'birthday' => ['nullable', 'date'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Mass-assign simple attributes
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->address = $validated['address'] ?? null;
        $user->phone_num = $validated['phone_num'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->job_title = $validated['job_title'] ?? null;
        $user->birthday = $validated['birthday'] ?? null;
        $user->github_url = $validated['github_url'] ?? null;
        $user->linkedin_url = $validated['linkedin_url'] ?? null;
        $user->instagram_url = $validated['instagram_url'] ?? null;

        // Optional password change
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Optional avatar upload (run: php artisan storage:link)
        //  If a new avatar was uploaded
        if ($request->hasFile('avatar')) {
            // Delete the old uploaded avatar if it exists and is not from /images/avatars/
            if ($user->avatar && str_starts_with($user->avatar, 'storage/avatars/')) {
                $oldPath = str_replace('storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store the new uploaded avatar in storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = 'storage/' . $path;
        }

        //  If user removes the avatar manually or leaves it empty
        if (!$request->hasFile('avatar') && !$user->avatar) {
            // Assign a random one from public/images/avatars/
            $user->avatar = User::randomAvatar();
        }


//        if ($request->hasFile('avatar')) {
//            $path = $request->file('avatar')->store('avatars', 'public');
//            $user->avatar = 'storage/' . $path; // <img src="{{ asset($user->avatar) }}">
//        }

        $user->save();

        return back()->with('success', 'Profile updated!');
    }
}
