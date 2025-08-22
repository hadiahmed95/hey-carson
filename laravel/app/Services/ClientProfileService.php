<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\SavedCardRepository;
use App\Exceptions\ApiErrorException;

class ClientProfileService
{
    public function __construct(
        private UserRepository $userRepository,
        private SavedCardRepository $cardRepository,
        private StripeService $stripeService
    ) {}

    /**
     * @throws ApiErrorException
     */
    public function updateProfile(User $user, array $data): User
    {
        if (isset($data['default_card_id'])) {
            $this->handleDefaultCardUpdate($user, $data['default_card_id']);
        } elseif (isset($data['remove_card'])) {
            $this->handleCardRemoval($data['remove_card']);
        } else {
            $this->handleProfileUpdate($user, $data);
        }

        return $this->userRepository->refresh($user);
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

    private function handleProfileUpdate(User $user, array $data): void
    {
        // Remove card-related keys from profile data
        $profileData = collect($data)->except(['default_card_id', 'remove_card'])->toArray();

        if (!empty($profileData)) {
            $this->userRepository->update($user, $profileData);
        }
    }
}
