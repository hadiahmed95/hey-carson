@extends('emails.template.v2-layout')

@section('content')
    <p>Hi {{ $clientName }}</p>

    <p>You’ve received a new message in your project workroom.</p>

    <p>Project: {{ $projectName }}</p>

    <span style="display: none">MessageId {{ $messageId }}</span>

    <p>Message: <strong style="font-weight: bold;">{!! $messageContent !!}</strong></p>

    <p><a href="{{ url('client/project/' . $projectId . '?messageId=' . $messageId) }}">[Go to Project]</a></p>

    <p>You can login to reply or reply directly from this email.</p>

    <p>Shopexperts Team</p>

    <br/>

@endsection

