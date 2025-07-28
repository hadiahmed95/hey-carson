@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true;
$appsLogo = true;
?>

@section('content')
    <div class='partner-message'>
        {!! $description !!}
    </div>
    <br>
    <br>
    <p class="">
        <a href="{{ $app_url }}" class="custom-button">
            Write your review
        </a>
    </p>
    <br>
@endsection
