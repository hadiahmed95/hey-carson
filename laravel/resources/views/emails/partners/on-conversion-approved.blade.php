@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hey {{$partner_name}},

        we’re writing to let you know that a new commission for {{$customer_name}}’s project,
        has been approved and added to your referral balance.
    </p>

    <p>
        Please click here to log in to our Partner dashboard, where you’ll be able to submit a withdrawal request.
    </p>

    <br>
    <p>
        The Shpexperts team.
    </p>
@endsection
