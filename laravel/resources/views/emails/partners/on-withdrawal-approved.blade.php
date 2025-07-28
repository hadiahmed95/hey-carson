@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hi {{$partner_name}},
        we’re writing to let you know that we’ve processed your
        request for withdrawal for {{$amount}} from {{$date}}.
    </p>

    <p>
        The funds should arrive to your {{$payment_method}} shortly.
    </p>

    <br>
    <p>
        The Shopexperts team.
    </p>
@endsection
