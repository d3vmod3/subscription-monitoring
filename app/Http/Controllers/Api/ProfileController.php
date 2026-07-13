<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Attachment;
use App\Services\AttachmentService;

class ProfileController extends Controller
{
    public function save(Request $request)
    {
        $user = $request->user();

         $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->last_name = $validated['last_name'];
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->email = $validated['email'];



        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);

        
    }

    public function saveProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image'],
        ]);

        $user = $request->user();

        $attachmentService = new AttachmentService();

        $old = Attachment::where('module', 'profile')
            ->where('module_id', $user->id)
            ->latest()
            ->first();

        if ($old) {
            $attachmentService->delete($old);
        }

        $attachment = $attachmentService->upload(
            $request->file('profile_picture'),
            'profile',
            $user->id,
            'profile_pictures'
        );

        return response()->json([
            'message' => 'Profile picture updated.',
            'url' => $attachmentService->temporaryUrl($attachment),
        ]);
    }

    public function me(Request $request)
    {
        $attachmentService = new AttachmentService();

        $user = $request->user()->load('profilePicture');

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'name' => $user->name,
            'email' => $user->email,
            'birthdate' => $user->birthdate,
            'created_at' => $user->created_at,
            'profile_picture' => $user->profilePicture
                ? $attachmentService->temporaryUrl($user->profilePicture)
                : null,
        ]);
    }
}
