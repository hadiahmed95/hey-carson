@extends('emails.template.v2-layout')

@section('content')

    <p>Hello,</p>

    <p>You are receiving this email because we received a password reset request for your account.</p>

    <br />

    <p class="text-center">
        <a href="{{ $resetPasswordLink }}" class="custom-button">
            Reset Password
        </a>
    </p>

    <br />

    <p>If you did not request a password reset, no further action is required.</p>

    <p class="text-small">
        If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br />

        <a href="{{ $resetPasswordLink }}" style='white-space: normal; word-break: break-word; width: 360px;'>{{ $resetPasswordLink }}</a>
    </p>

@endsection

