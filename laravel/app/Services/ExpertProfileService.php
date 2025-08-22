<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\SavedCardRepository;
use App\Repositories\ProfileRepository;
use App\Exceptions\ApiErrorException;

class ExpertProfileService
{
    public function __construct(
        private UserRepository $userRepository,
        private SavedCardRepository $cardRepository,
        private ProfileRepository $profileRepository,
        private StripeService $stripeService
    ) {}

    public function updateExpertProfile(User $user, array $data): User
    {
        if (isset($data['profile'])) {
            $this->handleProfileUpdate($user, $data['profile']);
        } elseif (isset($data['default_card_id'])) {
            $this->handleDefaultCardUpdate($user, $data['default_card_id']);
        } elseif (isset($data['remove_card'])) {
            $this->handleCardRemoval($data['remove_card']);
        } else {
            $this->handleUserUpdate($user, $data);
        }

        return $this->userRepository->refresh($user);
    }

    /**
     * @throws ApiErrorException
     */
    private function handleProfileUpdate(User $user, array $profileData): void
    {
        if (!$user->profile) {
            throw new ApiErrorException('User profile not found', 404);
        }

        $success = $this->profileRepository->updateUserProfile($user, $profileData);

        if (!$success) {
            throw new ApiErrorException('Failed to update profile', 500);
        }
    }

    /**
     * @throws ApiErrorException
     */
    private function handleDefaultCardUpdate(User $user, int $cardId): void
    {
        // Verify card belongs to user
        $card = $this->cardRepository->findById($cardId);
        if (!$card || $card->user_id !== $user->id) {
            throw new ApiErrorException('Card not found or does not belong to user', 404);
        }

        $this->cardRepository->setDefaultCard($user, $cardId);
    }

    /**
     * @throws ApiErrorException
     */
    private function handleCardRemoval(int $cardId): void
    {
        $card = $this->cardRepository->findById($cardId);
        if (!$card) {
            throw new ApiErrorException('Card not found', 404);
        }

        // Verify card belongs to authenticated user
        if ($card->user_id !== auth()->id()) {
            throw new ApiErrorException('Unauthorized to remove this card', 403);
        }

        try {
            $this->stripeService->detachPaymentMethod($card->payment_id);
            $this->cardRepository->delete($card);
        } catch (\Exception $e) {
            throw new ApiErrorException('Failed to remove card: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @throws ApiErrorException
     */
    private function handleUserUpdate(User $user, array $data): void
    {
        // Remove non-user fields from data
        $userData = collect($data)->except(['profile', 'default_card_id', 'remove_card'])->toArray();

        if (!empty($userData)) {
            $success = $this->userRepository->update($user, $userData);

            if (!$success) {
                throw new ApiErrorException('Failed to update user data', 500);
            }
        }
    }
}
