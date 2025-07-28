@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p>
        We’ve received your submission and we’re reviewing it now. We usually verify submissions once every business day.
    </p>
    <p>
        If you have several apps (or themes) listed on our reviews platform, you only need to submit once. We will connect
        all your products to the same account.
    </p>
    <p>
        Once you get access to the dashboard, you will be able to invite customers to write reviews, reply to reviews,
        answer questions, monitor traffic and much more.
    </p>
    <p>
        We also offer generous commissions for referrals to our design and development services. All information about
        referrals and commissions is also available within the dashboard.
    </p>
    <p>
        Let us know if you have any questions.
    </p>
    <!-- signature -->
    <p>
        Thanks<br />
        The Shopexperts team.
    </p>
@endsection
