@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hello,
    </p>

    <p>
        A new withdrawal request has been made by <strong>{{$partner_name}}</strong> at HeyCarson Partners.
    </p>

    <p>
        <strong>Amount:</strong> ${{$amount}}
    </p>

    <p>
        <strong>Commission:</strong> ${{$commission}}
    </p>

    <p>
        <strong>Date:</strong> {{$date}}
    </p>

    <p>
        <strong>PayPal Email:</strong> {{$paypal_email ?: 'N/A'}}
    </p>
@endsection
