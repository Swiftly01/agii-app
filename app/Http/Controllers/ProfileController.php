<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the user profile edit form
     */
    public function edit()
    {
        $user = User::find(Auth::id());
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'local_government' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'business_category' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'state' => $request->state,
                'local_government' => $request->local_government,
                'city' => $request->city,
                'address' => $request->address,
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'business_category' => $request->business_category,
                'facebook_url' => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'twitter_url' => $request->twitter_url,
                'whatsapp_number' => $request->whatsapp_number,
            ];

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {

                $image = $request->file('profile_image');
                $directory = public_path('profile-images');

                // Create folder if it doesn't exist
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Delete old profile image if it exists
                if (!empty($user->profile_image) && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                // Move new image
                $image->move($directory, $filename);

                // Save relative path
                $updateData['profile_image'] = 'profile-images/' . $filename;
            }



            // Update password only if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }


            $user->update($updateData);

            return redirect()->route('profile.edit')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }
}