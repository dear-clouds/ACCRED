@extends('en.Emails.Layouts.Master')

@section('message_content')
    <div>
        Hello,<br><br>
        To reset your password, complete this form: {{ route('password.reset', ['token' => $token]) }}.
        <br><br><br>
        Thank you,<br>
        Team Attendize
    </div>
@stop