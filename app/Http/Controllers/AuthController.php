<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Statamic\Facades\User;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember', true);

        // Find Statamic user
        $user = User::findByEmail($credentials['email']);
        if (! $user) {
            return back()->withErrors(['email' => 'সঠিক ইমেইল বা পাসওয়ার্ড প্রদান করুন (Invalid credentials)'])->withInput();
        }

        // Verify password
        if (! Hash::check($credentials['password'], $user->password())) {
            return back()->withErrors(['email' => 'ভুল পাসওয়ার্ড (Incorrect password)'])->withInput();
        }

        // Login using Laravel Auth
        Auth::login($user, $remember);

        return redirect()->route('dashboard')->with('success', 'স্বাগতম! আপনি সফলভাবে লগইন করেছেন (Successfully Logged In)');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'আপনি সফলভাবে লগআউট হয়েছেন (Logged Out)');
    }

    public function updateProfile(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ], [
            'name.required' => 'সদস্যের নাম (English) প্রদান করা আবশ্যক।',
            'name.max' => 'নাম সর্বোচ্চ ১০০ অক্ষরের হতে পারে।',
            'bangla_name.max' => 'বাংলা নাম সর্বোচ্চ ১০০ অক্ষরের হতে পারে।',
            'avatar_file.image' => 'প্রোফাইল ছবি অবশ্যই একটি ছবি ফাইল (JPG, PNG, WebP) হতে হবে।',
            'avatar_file.mimes' => 'ছবির ফরম্যাট অবশ্যই JPG, PNG, WebP বা GIF হতে হবে।',
            'avatar_file.max' => 'ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে। ফাইলটি অনুমোদিত সীমার চেয়ে বড়।',
        ]);

        $user = User::find($currentUser->id());
        if ($user) {
            $user->set('name', $validated['name']);
            if (!empty($validated['bangla_name'])) {
                $user->set('bangla_name', $validated['bangla_name']);
            }
            if (!empty($validated['phone'])) {
                $user->set('phone', $validated['phone']);
            }

            if ($request->hasFile('avatar_file')) {
                $file = $request->file('avatar_file');
                $filename = 'avatar_' . $user->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $filename);
                $user->set('avatar', '/uploads/avatars/' . $filename);
            }

            $user->save();
            \Illuminate\Support\Facades\Artisan::call('statamic:stache:clear');
        }

        return back()->with('success', 'প্রোফাইল ছবি ও তথ্য সফলভাবে আপডেট করা হয়েছে! (Profile Updated)');
    }

    public function updatePassword(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড প্রদান করুন।',
            'password.required' => 'নতুন পাসওয়ার্ড প্রদান করুন।',
            'password.min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মিলছে না (Password confirmation does not match)।',
        ]);

        $user = User::find($currentUser->id());
        if (! $user || ! Hash::check($validated['current_password'], $user->password())) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয় (Current password incorrect)']);
        }

        $user->password($validated['password']);
        $user->save();

        return back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে (Password Changed Successfully)');
    }
}
