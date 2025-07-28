@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        Hello,
    </p>

    <p>
        You have been invited to join <strong>{{$workspace}}</strong> at HeyCarson Partners.
    </p>
    <br>
    <p class="text-center">
        <a href="{{ $url }}" class="custom-button">
            Accept invitation
        </a>
    </p>
    <br>
    <p>
        Best regards,
        <br>
        The Shopexperts team.
    </p>
@endsection
