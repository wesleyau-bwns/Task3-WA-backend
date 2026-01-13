<?php

namespace App\Services\User;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Laravel\Facades\Image;

class UserService
{
    public function getProfile(User $user): User
    {
        return $user;
    }

    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Prevent updates if account is not active
            if ($user->account_status !== 'active') {
                throw new \Exception('Account is not active.');
            }

            // Handle avatar upload separately
            if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
                $data['avatar'] = $this->handleAvatarUpload($user, $data['avatar']);
            }

            // Update only allowed fields
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'username' => $data['username'] ?? $user->username,
                'phone_country_code' => $data['phone_country_code'] ?? $user->phone_country_code,
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
                'avatar' => $data['avatar'] ?? $user->avatar,
                'date_of_birth' => $data['date_of_birth'] ?? $user->date_of_birth,
                'country' => $data['country'] ?? $user->country,
                'address_line1' => $data['address_line1'] ?? $user->address_line1,
                'address_line2' => $data['address_line2'] ?? $user->address_line2,
                'city' => $data['city'] ?? $user->city,
                'state' => $data['state'] ?? $user->state,
                'postal_code' => $data['postal_code'] ?? $user->postal_code,
                'language' => $data['language'] ?? $user->language,
                'timezone' => $data['timezone'] ?? $user->timezone,
            ]);

            $original = $user->getOriginal();
            $user->update($data);
            $changes = $user->getChanges();

            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties([
                    'old' => array_intersect_key($original, $changes), // only changed fields
                    'new' => $changes
                ])
                ->log('updated user');

            return $user->fresh();
        });
    }

    public function updatePassword(User $user, array $data): User
    {
        if (isset($data['new_password'])) {
            $user->password = $data['new_password'];
            $original = $user->getOriginal();
            $user->save();

            $changes = $user->getChanges();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties([
                    'old' => array_intersect_key($original, $changes), // only changed fields
                    'new' => $changes,
                ])
                ->log('updated user password');
        }

        return $user->fresh();
    }

    private function handleAvatarUpload(User $user, UploadedFile $upload): string
    {
        try {
            // Process the image to save storage space
            $image = Image::read($upload)->resize(300, 300);
            $encodedImage =  $image->encodeByExtension($upload->getClientOriginalExtension(), quality: 70);

            // Save to storage/app/public/profile
            $filename = "profile/" . Str::random() . '.' . $upload->getClientOriginalExtension();
            Storage::disk('public')->put($filename, $encodedImage);

            // Delete old avatar if exists
            if ($user->avatar) {
                $relativePath = str_replace(config('app.url') . '/storage/', '', $user->avatar);
                Storage::disk('public')->delete($relativePath);
            }

            // Generate the public URL
            $publicUrl = config('app.url') . Storage::url($filename);
            return $publicUrl;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
