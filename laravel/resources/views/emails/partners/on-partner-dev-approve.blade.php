@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>Welcome <b>{{$name}},</b></p>

    <p>Great news! You can now access our partner area. Inside your account, you will find our referral partnership
        program details and incentives, and as an app or theme developer, you will also have access to our new customer
        reviews engine, to help you build a solid review profile and promote your app to our audience.</p>

    <p>
        Log in to your HeyCarson partner account <a href='{{env('PARTNERS_URL') . '/login'}}'>here</a>.
    </p>

    <p>
        If you would like to customize your referral ID or make changes to your app or dev profile information, or have
        any questions, concerns or suggestions, send an email to <a href="mailto:{{ env('PARTNERS_EMAIL') }}">{{ env('PARTNERS_EMAIL') }}</a>.
    </p>

    @if($with_password ?? false)
        <p>
            Please use this <a href='{{env('PARTNERS_URL') . '/reset-password?pcb=1'}}'>link</a> to reset your
            password and come up with a unique one!
        </p>
    @endif

    <p>
        We will also invite you to a Slack channel where we’re updating our early users on progress and providing advice
        and suggestions on how to best use our platform to support your growth.
    </p>
    <p>
        Let’s this started!
    </p>

    <!-- signature -->
    <p>
        The Shopexperts team.
    </p>
@endsection
