<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;

class ProfileRepository
{
    public function __construct(
        private Profile $model
    ) {}

    public function findByUserId(int $userId): ?Profile
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function updateUserProfile(User $user, array $data): bool
    {
        if (!$user->profile) {
            // Create profile if it doesn't exist
            return $user->profile()->create($data) !== null;
        }

        return $user->profile->update($data);
    }

    public function createForUser(User $user, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $user->profile()->create($data);
    }
}
