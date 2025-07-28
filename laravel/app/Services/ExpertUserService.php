<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class ExpertUserService
{
    /**
     * @throws \Exception
     */
    public function deactivate($expertId): void
    {
        $expert = User::find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->profile->update(['status' => 'inactive']);
            $expert->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    public function activate($expertId): void
    {
        $expert = User::withTrashed()->find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->profile->update(['status' => 'active']);
            $expert->restore();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Mark an expert user as available.
     *
     * @throws \Exception
     */
    public function markAvailable($expertId): void
    {
        $expert = User::find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->update(['availability_status' => 'available']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Mark an expert user as unavailable.
     *
     * @throws \Exception
     */
    public function markUnavailable($expertId): void
    {
        $expert = User::find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->update(['availability_status' => 'unavailable']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Mark an expert user as disable.
     *
     * @throws \Exception
     */
    public function markDisable($expertId): void
    {
        $expert = User::find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->update(['is_disable' => true]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Mark an expert user as disable.
     *
     * @throws \Exception
     */
    public function markEnable($expertId): void
    {
        $expert = User::find($expertId);

        if (!$expert || $expert->role_id !== Role::EXPERT) {
            throw new \Exception("Invalid Expert ID");
        }

        try {
            $expert->update(['is_disable' => false]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
