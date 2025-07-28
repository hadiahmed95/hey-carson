@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p>Thanks for posting a question.</p>

    <p>
        It's pending approval - we review questions a few times daily. You'll be notified when it's approved and published.
    </p>

    <p>While you're waiting, feel free to browse our <a href="{{ $app_url }}">app</a> and <a
            href="{{ $theme_url }}">theme</a> pages and ask more questions!</p>

    <!-- signature -->
    <p>
        Thank you<br />
        The Shopexperts team.
    </p>
@endsection
