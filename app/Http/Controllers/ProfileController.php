<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Image;

class ProfileController extends Controller
{
    const UPLOAD_DIR_P = '/uploads/profile/';
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        $request->user()->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $profileId = User::where('id', auth()->user()->id)->first();
      
        if ($request->hasFile('profile')) {
            $this->unlinkp($profileId->profile);
            $profileImg['profile'] = $this->uploadp($request->profile, 'image');
            $profileId->update($profileImg);
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function uploadp($file, $title = '')
    {
        $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $file_name = $timestamp . '-' . $title . '.' . $file->getClientOriginalExtension();
        Image::make($file)->resize(100, 100)->save(public_path() . self::UPLOAD_DIR_P . $file_name);
        return $file_name;
    }

    private function unlinkp($file)
    {
        if ($file != '' && file_exists(public_path() . self::UPLOAD_DIR_P . $file)) {
            @unlink(public_path() . self::UPLOAD_DIR_P . $file);
        }
    }
}
