<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InboundEmailsController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api.emails.auth')->prefix('/v1/partner-emails')->group(function () {
    Route::post('/signUpEmail', [\App\Http\Controllers\PartnersController::class, 'sendSignUpEmail']);
    Route::post('/welcomeEmail', [\App\Http\Controllers\PartnersController::class, 'sendWelcomeEmail']);
    Route::post('/resetPasswordEmail', [\App\Http\Controllers\PartnersController::class, 'sendResetPassword']);

    Route::post('/users/invite', [\App\Http\Controllers\PartnersController::class, 'sendUserInvite']);

    Route::post('/withdrawals/request', [\App\Http\Controllers\PartnersController::class, 'sendWithdrawalRequestEmail']);

    Route::post('/referral-new', [\App\Http\Controllers\PartnersController::class, 'sendReferralNew']);
    Route::post('/conversion-pending', [\App\Http\Controllers\PartnersController::class, 'sendConversionPending']);
    Route::post('/conversion-approved', [\App\Http\Controllers\PartnersController::class, 'sendConversionApproved']);
    Route::post('/withdrawal-approved', [\App\Http\Controllers\PartnersController::class, 'sendWithdrawalApproved']);

    Route::post('/claim-profile', [\App\Http\Controllers\PartnersController::class, 'sendClaimProfile']);

    // theme specific
    Route::post('/reviews/published', [\App\Http\Controllers\PartnersController::class, 'sendReviewPublished']);
    Route::post('/reviews/reply', [\App\Http\Controllers\PartnersController::class, 'sendReviewReply']);
    Route::post('/reviews/invite', [\App\Http\Controllers\PartnersController::class, 'sendReviewInvite']);

    // app specific
    Route::post('/app-reviews/published', [\App\Http\Controllers\PartnersController::class, 'sendAppReviewPublished']);
    Route::post('/app-reviews/reply', [\App\Http\Controllers\PartnersController::class, 'sendAppReviewReply']);
    Route::post('/app-reviews/invite', [\App\Http\Controllers\PartnersController::class, 'sendAppReviewInvite']);

    // app-qa
    Route::post('/apps-qa/submitted', [\App\Http\Controllers\PartnersController::class, 'sendAppQuestionSubmitted']);
    Route::post('/apps-qa/published-author', [\App\Http\Controllers\PartnersController::class, 'sendAppQuestionPublishedAuthor']);
    Route::post('/apps-qa/published-other', [\App\Http\Controllers\PartnersController::class, 'sendAppQuestionPublishedOther']);
    Route::post('/apps-qa/published-dev', [\App\Http\Controllers\PartnersController::class, 'sendAppQuestionPublishedDev']);
    Route::post('/apps-qa/answer-published', [\App\Http\Controllers\PartnersController::class, 'sendAppAnswerPublished']);

    // theme-qa
    Route::post('/themes-qa/submitted', [\App\Http\Controllers\PartnersController::class, 'sendThemeQuestionSubmitted']);
    Route::post('/themes-qa/published-author', [\App\Http\Controllers\PartnersController::class, 'sendThemeQuestionPublishedAuthor']);
    Route::post('/themes-qa/published-other', [\App\Http\Controllers\PartnersController::class, 'sendThemeQuestionPublishedOther']);
    Route::post('/themes-qa/published-dev', [\App\Http\Controllers\PartnersController::class, 'sendThemeQuestionPublishedDev']);
    Route::post('/themes-qa/answer-published', [\App\Http\Controllers\PartnersController::class, 'sendThemeAnswerPublished']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/php-get-info', function () {
    phpinfo();
});

Route::post('/inbound-processing', [InboundEmailsController::class, 'inboundData']);
Route::get('/inbound-email-logs/{date}', [\App\Http\Controllers\Logs::class, 'inboundEmailLogs'])->middleware('verify.token');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/v2/expert/register', [\App\Http\Controllers\NewDashboard\Expert\SignupController::class, 'register']);
Route::post('/free-quote', [\App\Http\Controllers\NewDashboard\Client\RequestController::class, 'freeQuote']);
Route::post('/get-matched', [\App\Http\Controllers\NewDashboard\Client\RequestController::class, 'getMatched']);
Route::post('/register/check', [AuthController::class, 'checkData']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/expert-list', [AuthController::class, 'expertList']);
Route::get('/client-list', [AuthController::class, 'clientList']);
Route::post('/generate-click/{slug}', [\App\Http\Controllers\ClickController::class, 'create']);
Route::get('/partner-projects/{partnerId}', [\App\Http\Controllers\PartnersDashController::class, 'fetchPartnerProjects']);

Route::get('/experts-profiles', [\App\Http\Controllers\ExpertProfileController::class, 'fetchAllExperts']);
Route::get('/experts-reviews', [\App\Http\Controllers\ExpertProfileController::class, 'getReviews']);
Route::get('/service-categories', [\App\Http\Controllers\ExpertProfileController::class, 'fetchServiceCategories']);
Route::get('/countries-with-experts', [\App\Http\Controllers\ExpertProfileController::class, 'fetchCountryExperts']);
Route::get('/cities-with-experts', [\App\Http\Controllers\ExpertProfileController::class, 'fetchCityExperts']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/payment/save-card', [\App\Http\Controllers\PaymentController::class, 'saveCard']);
    Route::post('/payment/buy-hours', [\App\Http\Controllers\PaymentController::class, 'buyHours']);
    Route::post('/payment/prepaid', [\App\Http\Controllers\PaymentController::class, 'prepaid']);
    Route::middleware('throttle:card-payment')->post('/payment/card-payment', [\App\Http\Controllers\PaymentController::class, 'cardPayment']);
//    Route::post('/payment/subtract', [\App\Http\Controllers\PaymentController::class, 'subtract']);

    Route::get('/sso/switch-to-old', [AuthController::class, 'switchToOldDashboard']);
    Route::get('/sso/switch-to-new', [AuthController::class, 'switchToNewDashboard']);
    Route::get('/auth-check', [AuthController::class, 'authCheck']);
    Route::post('/picture', [\App\Http\Controllers\FileController::class, 'picture']);

    Route::middleware('auth.role:' . Role::CLIENT)->prefix('/client')->group(function () {
        Route::get('/', \App\Http\Controllers\Client\DashboardController::class);
        Route::get('/dashboard', \App\Http\Controllers\Client\NewDashboardController::class);
        Route::get('/requests', [\App\Http\Controllers\Client\RequestController::class, 'all']);
        Route::get('/reviews', [\App\Http\Controllers\Client\ReviewController::class, 'all']);
        Route::get('/hours', [\App\Http\Controllers\AuthController::class, 'hours']);
        Route::post('/events', [\App\Http\Controllers\Client\EventController::class, 'updateBulk']);
        Route::get('/events', [\App\Http\Controllers\Client\EventController::class, 'all']);
        Route::post('/events/messages', [\App\Http\Controllers\Client\EventController::class, 'messages']);
        Route::post('/events/messages/{message}', [\App\Http\Controllers\Client\EventController::class, 'message']);
        Route::post('/events/{userEvent}', [\App\Http\Controllers\Client\EventController::class, 'update']);
        Route::get('/transactions', [\App\Http\Controllers\Client\SettingsController::class, 'transactions']);
        Route::get('/settings', [\App\Http\Controllers\Client\SettingsController::class, 'show']);
        Route::post('/settings', [\App\Http\Controllers\Client\SettingsController::class, 'update']);
        Route::get('/projects', [\App\Http\Controllers\Client\ProjectController::class, 'all']);
        Route::post('/projects', [\App\Http\Controllers\Client\ProjectController::class, 'create']);
        Route::get('/projects/{project}', [\App\Http\Controllers\Client\ProjectController::class, 'show']);
        Route::delete('/projects/{project}', [\App\Http\Controllers\Client\ProjectController::class, 'delete']);
        Route::post('/projects/{project}/complete', [\App\Http\Controllers\Client\ProjectController::class, 'complete']);
        Route::get('/projects/{project}/not_yet', [\App\Http\Controllers\Client\ProjectController::class, 'notYet']);
        Route::get('/messages', [\App\Http\Controllers\Client\MessageController::class, 'all']);
        Route::post('/projects/{project}/message', [\App\Http\Controllers\Client\MessageController::class, 'create']);
        Route::post('/projects/{project}/message/seen', [\App\Http\Controllers\Client\MessageController::class, 'update']);
        Route::post('/projects/{project}/message/{message}', [\App\Http\Controllers\Client\MessageController::class, 'edit']);
        Route::delete('/projects/{project}/message/{message}', [\App\Http\Controllers\Client\MessageController::class, 'delete']);
        Route::get('/projects/{project}/offer/{offer}', [\App\Http\Controllers\Client\OfferController::class, 'update']);
        Route::get('/projects/{project}/offer/{offer}/decline', [\App\Http\Controllers\Client\OfferController::class, 'decline']);
        Route::get('/questions', [\App\Http\Controllers\Client\QuestionController::class, 'all']);
        Route::post('/questions', [\App\Http\Controllers\Client\QuestionController::class, 'create']);
    });

    Route::middleware('auth.role:' . Role::CLIENT)->prefix('/v2/client')->group(function () {
        Route::get('/latest-requests', [\App\Http\Controllers\NewDashboard\Client\OverviewController::class, 'latestRequests']);
        Route::get('/featured-services-and-experts', [\App\Http\Controllers\NewDashboard\Client\OverviewController::class, 'featuredServicesAndExperts']);
        Route::get('/requests', [\App\Http\Controllers\NewDashboard\Client\RequestController::class, 'requests']);
        Route::post('/create-request', [\App\Http\Controllers\NewDashboard\Client\RequestController::class, 'create']);
        Route::get('/packaged-services', [\App\Http\Controllers\NewDashboard\Client\PackagedServiceController::class, 'packagedServices']);
        Route::get('/review-requests', [\App\Http\Controllers\NewDashboard\Client\ReviewController::class, 'reviewRequests']);
        Route::post('/reviews', [\App\Http\Controllers\NewDashboard\Client\ReviewController::class, 'store']);
        Route::get('/reviews', [\App\Http\Controllers\NewDashboard\Client\ReviewController::class, 'all']);
        Route::put('/reviews/{id}', [\App\Http\Controllers\NewDashboard\Client\ReviewController::class, 'update']);
        Route::get('/request/{request}', [\App\Http\Controllers\NewDashboard\Client\RequestController::class, 'request']);
        Route::get('/transactions', [\App\Http\Controllers\NewDashboard\Client\TransactionController::class, 'transactions']);
    });

    Route::middleware('auth.role:' . Role::EXPERT)->prefix('/v2/expert')->group(function () {
//        Route::get('/review-requests', [\App\Http\Controllers\Expert\ReviewController::class, 'reviewRequests']);
//        Route::post('/reviews', [\App\Http\Controllers\Expert\ReviewController::class, 'store']);
        Route::get('/reviews', [\App\Http\Controllers\Expert\ReviewController::class, 'all']);
//        Route::put('/reviews/{id}', [\App\Http\Controllers\Expert\ReviewController::class, 'update']);
        Route::get('/leads', [\App\Http\Controllers\NewDashboard\Expert\LeadController::class, 'leads']);
        Route::get('/stats', [\App\Http\Controllers\NewDashboard\Expert\LeadController::class, 'stats']);
    });

    Route::middleware('auth.role:' . Role::EXPERT)->prefix('/expert')->group(function () {
        Route::get('/', \App\Http\Controllers\Expert\DashboardController::class);
        Route::get('/dashboard/{expertStat}', \App\Http\Controllers\Expert\NewDashboardController::class);
        Route::get('/leads', [\App\Http\Controllers\Expert\LeadController::class, 'all']);
        Route::get('/my-listing', [\App\Http\Controllers\Expert\LeadController::class, 'all']);
        Route::get('/reviews', [\App\Http\Controllers\Expert\ReviewController::class, 'all']);
        Route::get('/profile', [\App\Http\Controllers\Expert\SettingsController::class, 'profile']);
        Route::get('/stats', [\App\Http\Controllers\Expert\SettingsController::class, 'all']);
        Route::get('/settings', [\App\Http\Controllers\Expert\SettingsController::class, 'show']);
        Route::post('/settings', [\App\Http\Controllers\Expert\SettingsController::class, 'update']);
        Route::post('/events', [\App\Http\Controllers\Expert\EventController::class, 'updateBulk']);
        Route::get('/events', [\App\Http\Controllers\Expert\EventController::class, 'all']);
        Route::post('/events/messages', [\App\Http\Controllers\Expert\EventController::class, 'messages']);
        Route::post('/events/messages/{message}', [\App\Http\Controllers\Expert\EventController::class, 'message']);
        Route::post('/events/{userEvent}', [\App\Http\Controllers\Expert\EventController::class, 'update']);
        Route::get('/payouts', [\App\Http\Controllers\Expert\PayoutsController::class, 'all']);
        Route::post('/payouts', [\App\Http\Controllers\Expert\PayoutsController::class, 'create']);
        Route::get('/projects', [\App\Http\Controllers\Expert\ProjectController::class, 'all']);
        Route::post('/projects', [\App\Http\Controllers\Expert\ProjectController::class, 'create']);
        Route::get('/projects/available', [\App\Http\Controllers\Expert\ProjectController::class, 'available']);
        Route::get('/projects/{project}', [\App\Http\Controllers\Expert\ProjectController::class, 'show']);
        Route::delete('/projects/{project}', [\App\Http\Controllers\Expert\ProjectController::class, 'delete']);
        Route::post('/projects/{project}/claim', [\App\Http\Controllers\Expert\ProjectController::class, 'claim']);
        Route::get('/projects/{project}/release', [\App\Http\Controllers\Expert\ProjectController::class, 'release']);
        Route::get('/projects/{project}/take', [\App\Http\Controllers\Expert\ProjectController::class, 'take']);
        Route::get('/messages', [\App\Http\Controllers\Expert\MessageController::class, 'all']);
        Route::put('/projects/{project}/completed', [\App\Http\Controllers\Expert\ProjectController::class, 'completed']);
        Route::post('/projects/{project}/message', [\App\Http\Controllers\Expert\MessageController::class, 'create']);
        Route::post('/projects/{project}/message/seen', [\App\Http\Controllers\Expert\MessageController::class, 'update']);
        Route::post('/projects/{project}/message/{message}', [\App\Http\Controllers\Expert\MessageController::class, 'edit']);
        Route::delete('/projects/{project}/message/{message}', [\App\Http\Controllers\Expert\MessageController::class, 'delete']);
        Route::post('/projects/{project}/offer', [\App\Http\Controllers\Expert\OfferController::class, 'create']);
        Route::put('/projects/{project}/offer', [\App\Http\Controllers\Expert\OfferController::class, 'update']);
        Route::get('/answers', [\App\Http\Controllers\Expert\AnswerController::class, 'all']);
        Route::post('/answers', [\App\Http\Controllers\Expert\AnswerController::class, 'create']);
        Route::put('/answers/{answer}', [\App\Http\Controllers\Expert\AnswerController::class, 'edit']);
    });

    Route::middleware('auth.role:' . Role::ADMIN)->prefix('/admin')->group(function () {
        Route::get('/', \App\Http\Controllers\Admin\DashboardController::class);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'show']);
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update']);
        Route::post('/settings/user', [\App\Http\Controllers\Admin\SettingsController::class, 'updateUser']);
        Route::post('/settings/password', [\App\Http\Controllers\Admin\SettingsController::class, 'changePassword']);

        Route::prefix('/projects')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ProjectController::class, 'all']);
            Route::post('/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'update']);
            Route::get('/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'show']);
            Route::post('/{project}/archive', [\App\Http\Controllers\Admin\ProjectController::class, 'archive']);
            Route::post('/{project}/restore', [\App\Http\Controllers\Admin\ProjectController::class, 'restore']);
            Route::post('/{project}/message', [\App\Http\Controllers\Admin\MessageController::class, 'create']);
            Route::post('/{project}/message/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'edit']);
        });

        Route::get('/clients', [\App\Http\Controllers\Admin\ClientController::class, 'all']);
        Route::get('/clients/{user}', [\App\Http\Controllers\Admin\ClientController::class, 'show']);
        Route::get('/experts', [\App\Http\Controllers\Admin\ExpertController::class, 'all']);
        Route::get('/experts/{user}', [\App\Http\Controllers\Admin\ExpertController::class, 'show'])->withTrashed();
        Route::post('/experts/{user}', [\App\Http\Controllers\Admin\ExpertController::class, 'update'])->withTrashed();
        Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutsController::class, 'all']);
        Route::post('/payouts/{payout}', [\App\Http\Controllers\Admin\PayoutsController::class, 'update']);
        Route::get('/questions', [\App\Http\Controllers\Admin\QuestionController::class, 'all']);
        Route::get('/questions/{question}', [\App\Http\Controllers\Admin\QuestionController::class, 'show']);
        Route::put('/questions/{question}', [\App\Http\Controllers\Admin\QuestionController::class, 'update']);
        Route::get('/transactions', [\App\Http\Controllers\Admin\SettingsController::class, 'transactions']);

        Route::post('/login-as/{user}', [\App\Http\Controllers\AuthController::class, 'loginAsUser']);
    });
});
Route::get('/test-experts', [\App\Http\Controllers\Admin\ExpertController::class, 'all']);
Route::get('/filter-options', [\App\Http\Controllers\Admin\ExpertController::class, 'getFilterOptions']);
Route::post('/login-as', [AuthController::class, 'loginAs'])->middleware('auth:sanctum')->withoutMiddleware('auth:sanctum');

Route::get('/test-clients', [\App\Http\Controllers\Admin\ClientController::class, 'all']);
Route::get('/lead-filter-options', [\App\Http\Controllers\Admin\ClientController::class, 'getLeadFilterOptions']);

Route::get('/test-quotes-sent', [\App\Http\Controllers\Admin\ProjectController::class, 'all']);
Route::get('/quote-filter-options', [\App\Http\Controllers\Admin\ProjectController::class, 'getQuoteFilterOptions']);
