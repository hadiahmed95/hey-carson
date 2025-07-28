@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hey {{$partner_name}},
        we’re writing to let you know that {{$customer_name}}—one of your clients,
        has registered with HeyCarson using your referral link.
    </p>

    <p>
        We’re assessing their inquiry and we usually reply within 1 business day, usually much faster.
    </p>

    <p>
        You can monitor the status of their projects with HeyCarson inside our Partner dashboard.
    </p>

    <p>
        Please click here to log in to your Partner account.
    </p>

    <br>
    <p>
        The Shopexperts team.
    </p>
@endsection
