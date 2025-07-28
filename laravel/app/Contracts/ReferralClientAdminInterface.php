<?php


namespace App\Contracts;


interface ReferralClientAdminInterface
{
    /**
     * Push stats for new project
     *
     * @param string $partnerId
     * @param string $programId
     * @return mixed
     */
    public function submitProject(string $partnerId, string $programId): mixed;
}
