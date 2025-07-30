@extends('emails.template.v2-layout')

@section('content')

    <strong>Hello!</strong>

    <p>You are receiving this email because we received a password reset request for your account. </p>

    <p>This password reset link will expire in 60 minutes.</p>

    <p>If you did not request a password reset, no further action is required.</p>

    <br>

    <br>

    <a href="{{ $url }}"
       style="background-color: #1f1f1f; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-family: Arial, sans-serif; font-size: 14px; display: flex; align-items: center; width: auto; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1f1f1f';"
    >
        <span style="flex-grow: 1; text-align: center;">Reset Password</span>
        <span style="margin-left: auto;">
            <img style="width: 20px; height: 20px;" src="{{ url('api/assets/email-resources/icons/arrow.png')  }}" alt="Arrow" />
        </span>
    </a>

    <br/>

@endsection

