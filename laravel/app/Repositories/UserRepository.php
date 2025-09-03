<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function __construct(
        private User $model
    ) {}

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByIdWithRelations(int $id, array $relations = []): ?User
    {
        /** @var User|null $user */
        $user = $this->model->query()->with($relations)->find($id);
        return $user;
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function refresh(User $user): User
    {
        return $user->refresh();
    }

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

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function createUserForReview(array $data): User
    {
        $nameParts = explode(' ', trim($data['full_name']), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $clientRoleId = Role::where('name', 'client')->first()->id;

        return User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $data['email'],
            'password' => Hash::make('password123'), // Default password
            'role_id' => $clientRoleId,
            'url' => $data['website'] ?? null,
            //'company_name' => $data['company_name'] ?? null, //Todo: Needs to add company_name in the users table
            'is_hired_on_shopexperts' => $data['is_hired_on_shopexperts'] ?? false,
        ]);
    }

    /**
     * Format user data for response
     *
     * @param User $user
     * @return array
     */
    public function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'full_name' => trim($user->first_name . ' ' . $user->last_name),
            'email' => $user->email,
            'website' => $user->url ?? '',
            'company_name' => $user->company_name ?? '',
            'is_hired_on_shopexperts' => $user->is_hired_on_shopexperts ?? false,
        ];
    }

    /**
     * Search users by full name
     *
     * @param string $search
     * @param int $limit
     * @return Collection
     */
    public function searchByName(string $search, int $limit = 20): Collection
    {
        if (empty($search)) {
            return collect([]);
        }

        return User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
            ->where('role_id', Role::CLIENT)
            ->select('id', 'first_name', 'last_name', 'email', 'url')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'website' => $user->url ?? '',
                ];
            });
    }
}
