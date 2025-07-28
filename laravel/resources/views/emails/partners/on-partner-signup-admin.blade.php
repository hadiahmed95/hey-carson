@extends('emails.template.v2-layout')

<?php
$modifiedSignature = true
?>

@section('content')
    <p>
        A new company is interested in joining the Partner Program.
    </p>

    <p>
        <strong>
            Name:&nbsp;
        </strong>
        {{$name}}
    </p>

    <p>
        <strong>
            Email:&nbsp;
        </strong>
        {{$email}}
    </p>

    <p>
        <strong>
            Company Name:&nbsp;
        </strong>
        {{$company_name}}
    </p>

    <p>
        <strong>
            Company URL:&nbsp;
        </strong>
        {{$company_url}}
    </p>

    <p>
        <strong>
            Program:&nbsp;
        </strong>
        {{$program_slug}}
    </p>
@endsection
