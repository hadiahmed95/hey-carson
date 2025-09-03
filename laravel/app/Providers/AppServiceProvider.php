<?php

namespace App\Providers;


use App\Models\Assignment;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Project;
use App\Observers\AssignmentObserver;
use App\Observers\OfferObserver;
use App\Observers\PaymentObserver;
use App\Models\Message;
use App\Models\Role;
use App\Models\User;
use App\Observers\MessageObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);
        Message::observe(MessageObserver::class);
        User::observe(UserObserver::class);
        Project::observe(ProjectObserver::class);
        Offer::observe(OfferObserver::class);
        Assignment::observe(AssignmentObserver::class);

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $type = $user->role_id === Role::CLIENT ? 'client' : 'expert';


            return 'http://app.shopexperts.com/reset-password?token=' . $token .
                '&email=' .$user->email . '&type=' . $type;
        });
    }
}
