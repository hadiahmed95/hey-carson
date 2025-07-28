<?php


namespace App\Contracts;


interface ReferralClientAccountInterface
{
    /**
     * Fetch customer details from Referral API by email
     *
     * @param string $email
     * @return mixed
     */
    public function getCustomer(string $email): mixed;
}
