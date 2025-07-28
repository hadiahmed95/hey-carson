<?php


namespace App\Http\Controllers;

use App\Events\SendEmail;
use App\Http\Requests\SendAppAnswerPublishedRequest;
use App\Http\Requests\SendAppQuestionPublishedAuthorRequest;
use App\Http\Requests\SendAppQuestionPublishedDevRequest;
use App\Http\Requests\SendAppQuestionPublishedOtherRequest;
use App\Http\Requests\SendAppQuestionSubmittedRequest;
use App\Http\Requests\SendAppReviewInviteRequest;
use App\Http\Requests\SendAppReviewPublishedRequest;
use App\Http\Requests\SendAppReviewReplyRequest;
use App\Http\Requests\SendClaimProfileRequest;
use App\Http\Requests\SendConversionApprovedRequest;
use App\Http\Requests\SendConversionPendingRequest;
use App\Http\Requests\SendReferralNewRequest;
use App\Http\Requests\SendResetPasswordRequest;
use App\Http\Requests\SendReviewInviteRequest;
use App\Http\Requests\SendReviewPublishedRequest;
use App\Http\Requests\SendReviewReplyRequest;
use App\Http\Requests\SendSignUpEmailRequest;
use App\Http\Requests\SendThemeAnswerPublishedRequest;
use App\Http\Requests\SendThemeQuestionPublishedAuthorRequest;
use App\Http\Requests\SendThemeQuestionPublishedDevRequest;
use App\Http\Requests\SendThemeQuestionPublishedOtherRequest;
use App\Http\Requests\SendThemeQuestionSubmittedRequest;
use App\Http\Requests\SendUserInviteRequest;
use App\Http\Requests\SendWelcomeEmailRequest;
use App\Http\Requests\SendWithdrawalApprovedRequest;
use App\Http\Requests\SendWithdrawalRequestEmailRequest;
use App\Mail\PartnersDash\Partners\SendAppReviewInviteEmail;
use App\Mail\PartnersDash\Partners\SendAppReviewPublishedEmail;
use App\Mail\PartnersDash\Partners\SendAppReviewReplyEmail;
use App\Mail\PartnersDash\Partners\SendConversionApprovedEmail;
use App\Mail\PartnersDash\Partners\SendConversionPendingEmail;
use App\Mail\PartnersDash\Partners\SendReferralNewEmail;
use App\Mail\PartnersDash\Partners\SendReviewInviteEmail;
use App\Mail\PartnersDash\Partners\SendReviewPublishedEmail;
use App\Mail\PartnersDash\Partners\SendReviewReplyEmail;
use App\Mail\PartnersDash\Partners\SendUserInviteEmail;
use App\Mail\PartnersDash\Partners\SendWithdrawalApprovedEmail;
use App\Mail\PartnersDash\Partners\SendWithdrawalRequestEmail;
use App\Mail\PartnersDash\QA\SendAppAnswerPublishedEmail;
use App\Mail\PartnersDash\QA\SendAppQuestionPublishedDevEmail;
use App\Mail\PartnersDash\QA\SendAppQuestionPublishedEmail;
use App\Mail\PartnersDash\QA\SendAppQuestionPublishedOtherEmail;
use App\Mail\PartnersDash\QA\SendAppQuestionSubmittedEmail;
use App\Mail\PartnersDash\QA\SendThemeAnswerPublishedEmail;
use App\Mail\PartnersDash\QA\SendThemeQuestionPublishedDevEmail;
use App\Mail\PartnersDash\QA\SendThemeQuestionPublishedEmail;
use App\Mail\PartnersDash\QA\SendThemeQuestionPublishedOtherEmail;
use App\Mail\PartnersDash\QA\SendThemeQuestionSubmittedEmail;
use App\Mail\PartnersDash\SendAdminPartnerSignUpEmail;
use App\Mail\PartnersDash\SendClaimAppProfileAdminEmail;
use App\Mail\PartnersDash\SendClaimAppProfileEmail;
use App\Mail\PartnersDash\SendPartnerSignUpEmail;
use App\Mail\PartnersDash\SendPartnerWelcomeEmail;
use App\Mail\PartnersDash\SendResetPasswordEmail;
use Illuminate\Http\JsonResponse;

/**
 * Handle creating/listing users that belong to a partner record in referral api
 *
 * BAD USAGE OF ELOQUENT DUE TO LIMITED TIME FRAME
 *
 * Class PartnersController
 * @package App\Http\Controllers\API\v1
 */
class PartnersController
{
    /**
     * @param SendSignUpEmailRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendSignUpEmail(SendSignUpEmailRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendPartnerSignUpEmail([
                'name' => $data['name']
            ])
        );

        $to = [
            env('PARTNERS_EMAIL'),
            env('GABRIEL_EMAIL'),
            env('JON_EMAIL'),
        ];

        foreach ($to as $email) {
            SendEmail::dispatch(
                $email,
                new SendAdminPartnerSignUpEmail([
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'company_name'  => $data['company_name'],
                    'company_url'   => $data['company_url'],
                    'program_slug'  => $data['program_slug'],
                ])
            );
        }

        return $response->setStatusCode(200);
    }

    /**
     * @param SendWelcomeEmailRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendWelcomeEmail(SendWelcomeEmailRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendPartnerWelcomeEmail([
                'name' => $data['name'],
                'with_password' => $data['with_password'] == '1'
            ])
        );

        return $response
            ->setStatusCode(200);
    }

    /**
     * @param SendResetPasswordRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendResetPassword(SendResetPasswordRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();


        SendEmail::dispatch(
            $data['email'],
            new SendResetPasswordEmail(
                '',
                $data['email'],
                $data['url']
            )
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendReviewPublishedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendReviewPublished(SendReviewPublishedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendReviewPublishedEmail([
                'email' => $data['email'],
                'theme_name' => $data['theme_name'],
                'developer_name' => $data['developer_name'],
                'login_url' => $data['login_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendReviewReplyRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendReviewReply(SendReviewReplyRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendReviewReplyEmail([
                'email' => $data['email'],
                'theme_name' => $data['theme_name'],
                'user_name' => $data['user_name'],
                'developer_name' => $data['developer_name'],
                'theme_url' => $data['theme_url']
            ])
        );

        SendEmail::dispatch(
            env('JON_EMAIL'),
            new SendReviewReplyEmail([
                'email' => $data['email'],
                'theme_name' => $data['theme_name'],
                'user_name' => $data['user_name'],
                'developer_name' => $data['developer_name'],
                'theme_url' => $data['theme_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendReviewInviteRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendReviewInvite(SendReviewInviteRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendReviewInviteEmail([
                'email' => $data['email'],
                'theme_name' => $data['theme_name'],
                'developer_name' => $data['developer_name'],
                'theme_url' => $data['theme_url'],
                'description' => $data['description'] ?? ''
            ])
        );

        SendEmail::dispatch(
            env('JON_EMAIL'),
            new SendReviewInviteEmail([
                'email' => $data['email'],
                'theme_name' => $data['theme_name'],
                'developer_name' => $data['developer_name'],
                'theme_url' => $data['theme_url'],
                'description' => $data['description'] ?? ''
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppReviewPublishedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppReviewPublished(SendAppReviewPublishedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppReviewPublishedEmail([
                'email' => $data['email'],
                'app_name' => $data['app_name'],
                'developer_name' => $data['developer_name'],
                'login_url' => $data['login_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppReviewReplyRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppReviewReply(SendAppReviewReplyRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppReviewReplyEmail([
                'email' => $data['email'],
                'app_name' => $data['app_name'],
                'user_name' => $data['user_name'],
                'developer_name' => $data['developer_name'],
                'app_url' => $data['app_url']
            ])
        );

        SendEmail::dispatch(
            env('JON_EMAIL'),
            new SendAppReviewReplyEmail([
                'email' => $data['email'],
                'app_name' => $data['app_name'],
                'user_name' => $data['user_name'],
                'developer_name' => $data['developer_name'],
                'app_url' => $data['app_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppReviewInviteRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppReviewInvite(SendAppReviewInviteRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppReviewInviteEmail([
                'email' => $data['email'],
                'app_name' => $data['app_name'],
                'developer_name' => $data['developer_name'],
                'app_url' => $data['app_url'],
                'description' => $data['description'] ?? ''
            ])
        );

        SendEmail::dispatch(
            env('JON_EMAIL'),
            new SendAppReviewInviteEmail([
                'email' => $data['email'],
                'app_name' => $data['app_name'],
                'developer_name' => $data['developer_name'],
                'app_url' => $data['app_url'],
                'description' => $data['description'] ?? ''
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendUserInviteRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendUserInvite(SendUserInviteRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendUserInviteEmail([
                'email' => $data['email'],
                'workspace' => $data['workspace'],
                'url' => $data['url'],
                'inviter' => $data['inviter'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendWithdrawalRequestEmailRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendWithdrawalRequestEmail(SendWithdrawalRequestEmailRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            'admin@heycarson.com',
            new SendWithdrawalRequestEmail([
                'partner_name' => $data['partner_name'],
                'amount' => $data['amount'],
                'commission' => $data['commission'],
                'date' => $data['date'],
                'paypal_email' => $data['paypal_email'] ?? '',
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendReferralNewRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendReferralNew(SendReferralNewRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendReferralNewEmail([
                'partner_name' => $data['partner_name'],
                'customer_name' => $data['customer_name'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendConversionPendingRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendConversionPending(SendConversionPendingRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendConversionPendingEmail([
                'partner_name' => $data['partner_name'],
                'customer_name' => $data['customer_name'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendConversionApprovedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendConversionApproved(SendConversionApprovedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendConversionApprovedEmail([
                'partner_name' => $data['partner_name'],
                'customer_name' => $data['customer_name'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendWithdrawalApprovedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendWithdrawalApproved(SendWithdrawalApprovedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendWithdrawalApprovedEmail([
                'partner_name' => $data['partner_name'],
                'amount' => $data['amount'],
                'date' => $data['date'],
                'payment_method' => $data['payment_method'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendClaimProfileRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendClaimProfile(SendClaimProfileRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        $to = [ env('PARTNERS_EMAIL'), env('JON_EMAIL'), env('GABRIEL_EMAIL') ];

        foreach ($to as $email) {
            // Construct email message
            $message = new SendClaimAppProfileAdminEmail([
                'company' => $data['company'],
                'website' => $data['website'],
                'name' => $data['name'],
                'email' => $data['email'],
                'developer' => $data['developer']
            ]);

            $emailSubject = 'Claim app profile: ' . $data['developer'];

            $message->to($email);
            $message->replyTo($data['email'], $data['name']);
            $message->subject($emailSubject);

            // queue the message
            \Mail::queue($message);
        }

        // send email to user
        $message = new SendClaimAppProfileEmail(
            [
                'name' => $data['name'],
            ]
        );
        $message->to($data['email']);

        // queue the message
        \Mail::queue($message);

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppQuestionSubmittedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppQuestionSubmitted(SendAppQuestionSubmittedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppQuestionSubmittedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'app_url' => $data['app_url'],
                'theme_url' => $data['theme_url']
            ])
        );

        return $response->setStatusCode(200);
    }


    /**
     * @param SendThemeQuestionSubmittedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendThemeQuestionSubmitted(SendThemeQuestionSubmittedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendThemeQuestionSubmittedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'app_url' => $data['app_url'],
                'theme_url' => $data['theme_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppQuestionPublishedAuthorRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppQuestionPublishedAuthor(SendAppQuestionPublishedAuthorRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppQuestionPublishedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendThemeQuestionPublishedAuthorRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendThemeQuestionPublishedAuthor(SendThemeQuestionPublishedAuthorRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendThemeQuestionPublishedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppQuestionPublishedOtherRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppQuestionPublishedOther(SendAppQuestionPublishedOtherRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppQuestionPublishedOtherEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
                'app_name' => $data['app_name']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppQuestionPublishedDevRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppQuestionPublishedDev(SendAppQuestionPublishedDevRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppQuestionPublishedDevEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
                'app_name' => $data['app_name'],
                'partner_dash_url' => $data['partner_dash_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendThemeQuestionPublishedOtherRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendThemeQuestionPublishedOther(SendThemeQuestionPublishedOtherRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendThemeQuestionPublishedOtherEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
                'theme_name' => $data['theme_name']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendThemeQuestionPublishedDevRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendThemeQuestionPublishedDev(SendThemeQuestionPublishedDevRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendThemeQuestionPublishedDevEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url'],
                'theme_name' => $data['theme_name'],
                'partner_dash_url' => $data['partner_dash_url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendAppAnswerPublishedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendAppAnswerPublished(SendAppAnswerPublishedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendAppAnswerPublishedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url']
            ])
        );

        return $response->setStatusCode(200);
    }

    /**
     * @param SendThemeAnswerPublishedRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function sendThemeAnswerPublished(SendThemeAnswerPublishedRequest $request, JsonResponse $response): JsonResponse
    {
        $data = $request->validated();

        SendEmail::dispatch(
            $data['email'],
            new SendThemeAnswerPublishedEmail([
                'email' => $data['email'],
                'name' => $data['name'],
                'url' => $data['url']
            ])
        );

        return $response->setStatusCode(200);
    }

}
