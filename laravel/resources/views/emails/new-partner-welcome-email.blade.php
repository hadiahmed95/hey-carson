@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>Hello <b>{{$name}},</b></p>

    <p>Welcome aboard to the <strong>shopexperts</strong> partner network! We're thrilled to have you with us.</p>

    <h3>Step 1: Set Up Your Account</h3>
    <p><a href="{{env('PARTNERS_URL')}}/reset-password">Click here</a> to set up your password and get started.</p>

    <h3>Step 2: Login to Your Partner Area</h3>
    <p>
        Once you've <a href="{{env('PARTNERS_URL')}}">logged in</a>,
        you can track your referrals and commissions effortlessly.
    </p>


    <p style="font-weight: 600;">Commission Structure</p>
    <p style="margin-bottom: 8px !important;">Here's a sneak peek at how you can earn:</p>
    <ul style="list-style-type: none;">
        <li><strong>Level 1:</strong> Up to USD $15k in referred volume – earn 10% for every initial referral purchase.</li>
        <li><strong>Level 2:</strong> Up to USD $25k in referred volume – earn 12.5% for every initial referral purchase.</li>
        <li><strong>Level 3:</strong> Up to USD $50k in referred volume – earn 15% for every initial referral purchase.</li>
        <li><strong>Level 4:</strong> Above USD $50k in referred volume – earn 18% for every initial referral purchase, and 5% for every repeat purchase.</li>
    </ul>

    <h3>Step 3: Use Your Co-Branded Page</h3>
    <p>Send us a copy of your logo to  <a href="mailto:{{ env('PARTNERS_EMAIL') }}">{{ env('PARTNERS_EMAIL') }}</a>, and we'll set up your co-branded page.
    Share it with your referrals, and once they submit their project using the form on your page,
    you'll start earning commissions.</p>

    <h3>Important: Make sure to use your co-branded page to track conversions from your referrals effectively!</h3>

    <p>If you have any questions, don't hesitate to reply to this email.</p>


    <!-- signature -->
    <p>
        Cheers,
        <br />
        The Shopexperts team
    </p>

    <p>PS: We're here to help you succeed. Let's make this partnership great! &#x1F680;</p>
@endsection
