@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p>Your question has been published on <a href="{{ $url }}">this page</a></p>
    <p>We’ve notified the developer and previous reviewers (if applicable), to help you get the best possible answer to your
        question. You will be notified when answers get posted.
    </p>
    <!-- signature -->
    <p>
        Thank you<br />
        The Shopexperts team.
    </p>
@endsection
