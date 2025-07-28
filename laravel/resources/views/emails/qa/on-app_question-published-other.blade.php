@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p><p>A fellow merchant has asked a question about <b>{{ $app_name }}</b>. Since you’ve previously written a review for this app, we thought you might be able to help out.</p></p>

    <p><a href="{{ $url }}">Click here</a> to read their question and post a thoughtful answer based on your
        experience. </p>

    </p>
    <!-- signature -->
    <p>
        Thank you<br />
        The Shopexperts team.
    </p>
@endsection
