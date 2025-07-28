@extends('emails.template.v2-layout')

@section('content')

    <p>Dear {{ $merchantName['firstname'] . ' ' . $merchantName['lastname'] }}</p>

    <p>We are excited to announce that <strong>HeyCarson</strong> has now become <strong>Shopexperts</strong>! As part of this rebranding, your account has been seamlessly transferred to our new platform. </p>

    <p>Please rest assured that you can log in with your existing credentials and enjoy an enhanced experience with all the features you love and trust.</p>

    <p>Thank you for being an invaluable part of our journey. We look forward to continuing to support your business with our expert services.</p>

    <br>

    <br>

    <a href="https://app.shopexperts.com/client/login"
       style="background-color: #1f1f1f; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-family: Arial, sans-serif; font-size: 14px; display: flex; align-items: center; width: auto; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1f1f1f';"
    >
        <span style="flex-grow: 1; text-align: center;">Visit your account at Shopexperts</span>
        <span style="margin-left: auto;">
            <img style="width: 20px; height: 20px;" src="{{ url('api/assets/email-resources/icons/arrow.png') }}" alt="Arrow" />
        </span>
    </a>

    <br/>

@endsection

