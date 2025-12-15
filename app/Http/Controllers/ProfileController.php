<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
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
        $user = $request->user();
        
        // Update other fields first (exclude avatar from mass assignment)
        $data = $request->validated();
        unset($data['avatar']); // Remove avatar from validated data
        
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            
            Log::info('Avatar upload started', [
                'user_id' => $user->id,
                'file_name' => $avatar->getClientOriginalName(),
                'file_size' => $avatar->getSize(),
                'mime_type' => $avatar->getMimeType()
            ]);
            
            // Validate file
            if (!$avatar->isValid()) {
                Log::error('Avatar file is not valid', ['error' => $avatar->getError()]);
                return Redirect::route('profile.edit')
                    ->withErrors(['avatar' => 'File ảnh không hợp lệ.'])
                    ->withInput();
            }
            
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                try {
                    Storage::disk('public')->delete($user->avatar);
                    Log::info('Old avatar deleted', ['path' => $user->avatar]);
                } catch (\Exception $e) {
                    // Log error but continue
                    Log::warning('Failed to delete old avatar: ' . $e->getMessage());
                }
            }
            
            // Generate unique filename
            $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            
            // Store file to storage/app/public/avatars
            try {
                $path = $avatar->storeAs('avatars', $avatarName, 'public');
                
                Log::info('Avatar stored', ['path' => $path, 'full_path' => storage_path('app/public/' . $path)]);
                
                if (!$path) {
                    Log::error('Avatar store returned false');
                    return Redirect::route('profile.edit')
                        ->withErrors(['avatar' => 'Không thể lưu file ảnh.'])
                        ->withInput();
                }
                
                // Verify file was actually saved
                if (!Storage::disk('public')->exists($path)) {
                    Log::error('Avatar file does not exist after store', ['path' => $path]);
                    return Redirect::route('profile.edit')
                        ->withErrors(['avatar' => 'File đã được upload nhưng không tìm thấy trong storage.'])
                        ->withInput();
                }
                
                $user->avatar = $path;
                Log::info('Avatar path set on user model', ['avatar' => $user->avatar]);
            } catch (\Exception $e) {
                Log::error('Avatar upload error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return Redirect::route('profile.edit')
                    ->withErrors(['avatar' => 'Lỗi khi upload ảnh: ' . $e->getMessage()])
                    ->withInput();
            }
        }

        try {
            $saved = $user->save();
            Log::info('User saved', ['saved' => $saved, 'avatar' => $user->avatar, 'dirty' => $user->getDirty()]);
        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'Không thể cập nhật hồ sơ: ' . $e->getMessage()])
                ->withInput();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
