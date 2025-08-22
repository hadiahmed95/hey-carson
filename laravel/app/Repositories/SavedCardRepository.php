<?php

namespace App\Repositories;

use App\Models\SavedCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SavedCardRepository
{
    public function __construct(
        private SavedCard $model
    ) {}

    public function findById(int $id): ?SavedCard
    {
        return $this->model->find($id);
    }

    public function getUserCards(User $user): Collection
    {
        return $user->savedCards;
    }

    public function setDefaultCard(User $user, int $cardId): void
    {
        $user->savedCards->each(function (SavedCard $savedCard) use ($cardId) {
            $savedCard->update([
                'default' => $savedCard->id === $cardId
            ]);
        });
    }

    public function delete(SavedCard $card): bool
    {
        return $card->delete();
    }
}
