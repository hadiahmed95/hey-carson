<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user.
     * @return User The user instance.
     */
    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by their partner_id.
     *
     * @param string $partnerId
     * @return Collection The user instance.
     */
    public function findUsersByPartnerId(string $partnerId): Collection
    {
        return User::where('partner_id', $partnerId)->pluck('id');
    }
}
