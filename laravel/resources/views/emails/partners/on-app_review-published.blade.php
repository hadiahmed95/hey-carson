@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Dear {{$developer_name}},
    </p>

    <p>
        We just wanted to let you know that a new review for your {{$app_name}} app has been published. You may
        <a href="{{$login_url}}">log in</a> to your Partner account to read the review or to reply to it.
    </p>

    <p>
        We appreciate your dedication for creating high-quality apps for Shopify merchants, and we hope that the
        feedback will be helpful as you continue to develop your apps.
    </p>

    <p>
        Best regards,
        <br>
        The Shopexperts team
    </p>
@endsection
