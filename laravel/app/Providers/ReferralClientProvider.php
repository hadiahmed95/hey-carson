<?php

namespace App\Providers;

use App\Services\ReferralClientService;
use Illuminate\Support\ServiceProvider;

class ReferralClientProvider extends ServiceProvider
{

    protected bool $defer = true;

    public function register(): void
    {
        $this->app->singleton('App\Contracts\ReferralClientPublicInterface', function () {
            $baseConfig = config('referral_api.basic_config');
            $baseConfig['headers'] = array_merge(
                $baseConfig['headers'],
                config('referral_api.public')['headers']
            );

            return new ReferralClientService($baseConfig);
        });

        $this->app->singleton('App\Contracts\ReferralClientAdminInterface', function () {
            return new ReferralClientService(array_merge(
                config('referral_api.basic_config'),
                ['auth' => config('referral_api.su-auth')]
            ));
        });

        $this->app->singleton('App\Contracts\ReferralClientAccountInterface', function () {
            return new ReferralClientService(array_merge(
                config('referral_api.basic_config'),
                ['auth' => config('referral_api.account')]
            ));
        });

        $this->app->alias('App\Contracts\ReferralClientPublicInterface', 'referral_api_public');
        $this->app->alias('App\Contracts\ReferralClientAdminInterface', 'referral_api_admin');
        $this->app->alias('App\Contracts\ReferralClientAccountInterface', 'referral_api_account');
    }

    public function provides(): array
    {
        return [
            'referral_api_public',
            'referral_api_admin',
            'referral_api_account',
            'App\Contracts\ReferralClientPublicInterface',
            'App\Contracts\ReferralClientAdminInterface',
            'App\Contracts\ReferralClientAccountInterface'
        ];
    }

}
