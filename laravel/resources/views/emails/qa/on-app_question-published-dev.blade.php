@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p>A Shopify merchant wants to know more about <b>{{ $app_name }}</b>, and we seek your expertise in order to assist them.</p>

    <p><a href="{{ $url }}">Click here</a>  to read their question on our website. Please note that the reply input on the listing page, is intended for reviewers.</p>

    <p>You may respond to their question from inside our <a href="{{ $partner_dash_url }}">Partners Dashboard</a>, where you'll find a dedicated form with advanced functionality.</p>

    <p>Your answer will be displayed on our website right away alongside their question, though you will still be able to make edits post-submission.</p>
    <p>Should you have any questions, feel free to contact us at <a href="mailto:hello@heycarson.com" style="color: #0000ff; text-decoration: underline;">hello@heycarson.com</a>.</p>
    <!-- signature -->
    <p>
        Thank you,<br />
        The Shopexperts team.
    </p>
@endsection
