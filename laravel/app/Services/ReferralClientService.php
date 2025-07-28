<?php

namespace App\Services;

use App\Contracts\ReferralClientAdminInterface;
use App\Contracts\ReferralClientPublicInterface;
use App\Contracts\ReferralClientAccountInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ReferralClientService implements ReferralClientPublicInterface, ReferralClientAdminInterface, ReferralClientAccountInterface
{
    public Client $refClient;

    public function __construct($config)
    {
        $this->refClient = new Client($config);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $clickId
     * @param mixed $metadata
     * @return mixed
     */
    public function createCustomer(string $name, string $email, string $clickId, array $metadata = []): mixed
    {
        try {
            $data = [
                'click_id' => $clickId,
                'name' => $name,
                'email' => $email,
                'meta_data' => $metadata,
            ];

            $response = $this->refClient->post(
                '/v1/customers',
                [
                    'json' => $data
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return [];
        }
    }

    /**
     * @param string $partnerId
     * @param string $programId
     * @return mixed
     */
    public function submitProject(string $partnerId, string $programId): mixed
    {
        try {
            $response = $this->refClient->post(
                '/v1/stats',
                [
                    'json' => [
                        'name'       => 'task_submitted_first',
                        'value'      => 1,
                        'timestamp'  => now(),
                        'partner_id' => $partnerId,
                        'program_id' => $programId,
                        'account_id' => env('REF_ACCOUNT_ID'),
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return [];
        }
    }

    public function createConversion(
        string $clickId,
        string $customerEmail,
        string $amount,
        mixed $commissionSlug,
        string $tierGroup,
        array $metadata = []
    ) {
        try {
            $response = $this->refClient->post(
                '/v1/conversions',
                [
                    'json' => [
                        'click_id' => $clickId,
                        'customer_email' => $customerEmail,
                        'account_id' => env('REF_ACCOUNT_ID'),

                        'amount' => $amount,
                        'commission_slug' => $commissionSlug,
                        'tier_group' => $tierGroup,

                        'metadata' => $metadata ?: null,
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return [];
        }
    }

    /**
     * Fetch the partner information by partner ID.
     *
     * @param string $partnerId The ID of the partner to retrieve.
     *
     * @return mixed The partner data in an associative array or an empty array in case of failure.
     */
    public function getPartner(string $partnerId): mixed
    {
        try {
            $response = $this->refClient->get("/v1/partners/{$partnerId}", [
                'headers' => [
                    'X-Request-Source' => 'laravel',
                ]
            ]);

            return json_decode($response->getBody()->getContents());

        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return [];
        }
    }

    /**
     * @inerhitDoc
     */
    public function getCustomer(string $email): mixed
    {
        try {
            $uri = '/v1/customers';

            if ($email) {
                $uri .= '/' . $email;
            }

            $response = $this->refClient->get(
                $uri,
                [
                    'query' => [
                        'email'      => $email ? '1' : null,
                        'account_id' => env('REF_ACCOUNT_ID'),
                    ],
                    'headers' => [
                        'X-Silent-Exception' => `1`
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return null;
        }
    }

    /**
     * @inerhitDoc
     */
    public function getClick(string $clickId): mixed
    {
        try {
            $response = $this->refClient->get(
                "/v1/clicks/{$clickId}"
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return null;
        }
    }

    /**
     * @inerhitDoc
     */
    public function getCommissionTier(string $referralId, $tierGroup): mixed
    {
        try {
            $response = $this->refClient->get("/v1/referral-codes/{$referralId}:commission_tier", [
                'query' => [
                    'tier_group' => $tierGroup,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            Log::channel('partner-dash')->error($exception);
            return null;
        }
    }
}
