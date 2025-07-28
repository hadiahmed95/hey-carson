@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>Hi <b>{{$name}},</b></p>

    <p>
        Thank you for applying to our partner program!
    </p>

    <p>
        Your application is successfully submitted and currently waiting to be reviewed.
    </p>
    <p>
        Please note it may take up to 3 working days for this to be completed.
    </p>
    <p>
        Once it is approved, you will receive a welcome email from us containing login instructions and other details.
    </p>
    <p>
        Thanks again for your interest in our program!
    </p>

    <!-- signature -->
    <p>
        The Shopexperts team.
    </p>
@endsection
