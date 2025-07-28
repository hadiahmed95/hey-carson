@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
?>

@section('content')
    <p>Hi <b>{{ $name }},</b></p>

    <p>Your question has received an answer. <a href="{{ $url }}">Go here</a> to check it out. </p>

    <p>If you keep posting questions about apps and themes you’re considering, we will keep trying to help you by attracting
        developers and experienced users to help you out.
    </p>

    <!-- signature -->
    <p>
        Thank you<br />
        The Shopexperts team.
    </p>
@endsection
