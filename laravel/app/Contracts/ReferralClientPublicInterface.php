<?php


namespace App\Contracts;


interface ReferralClientPublicInterface
{
    /**
     * Create customer in Referral API database
     *
     * @param string $name
     * @param string $email
     * @param string $clickId
     * @param mixed|null $metadata
     */
    public function createCustomer(string $name, string $email, string $clickId, array $metadata = []);

    /**
     * Create conversion in Referral API database
     *
     * @param string $clickId
     * @param string $customerEmail
     * @param string $amount
     * @param mixed $commissionSlug
     * @param string $tierGroup
     * @param mixed|null $metadata
     */
    public function createConversion(
        string $clickId,
        string $customerEmail,
        string $amount,
        mixed $commissionSlug,
        string $tierGroup,
        array $metadata = []
    );

    /**
     * Fetch partner details from Referral API by partner ID
     *
     * @param string $partnerId
     * @return mixed
     */
    public function getPartner(string $partnerId): mixed;

    /**
     * Fetch customer click details from Referral API by partner ID
     *
     * @param string $clickId
     * @return mixed
     */
    public function getClick(string $clickId): mixed;

    /**
     * Fetch standard commission tier by Referral ID
     *
     * @param string $referralId
     * @param $tierGroup
     * @return mixed
     */
    public function getCommissionTier(string $referralId, $tierGroup): mixed;
}
