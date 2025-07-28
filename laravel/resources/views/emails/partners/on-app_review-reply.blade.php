@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Dear {{$user_name}},
    </p>

    <p>
        We just wanted to let you know that {{$developer_name}} has responded to your review of their {{$app_name}} app.
        You can view their reply on <a href='{{$app_url}}'>our website</a>.
    </p>

    <p>
        Thank you for your time and effort in reviewing the app, and we hope that this dialogue can lead to better
        apps for all Shopify merchants.
    </p>

    <p>
        Best regards,
        <br>
        The Shopexperts team.
    </p>
@endsection
