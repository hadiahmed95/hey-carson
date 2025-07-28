@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hey {{$partner_name}},
        we’re writing to let you know that {{$customer_name}}—one of your referred clients,
        has just placed a payment for their project.
    </p>

    <p>
        Once the project has been completed, the commission for the referral will be added to your balance.
    </p>

    <p>
        Please click here to log in to your Partner account.
    </p>

    <br>
    <p>
        The Shopexperts team.
    </p>
@endsection
