@extends('Shared.Layouts.MasterWithoutMenus')

@section('title', trans("User.login"))

@section('content')
    {!! Form::open(array('url' => route("login"))) !!}
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="logo">
                        {!!HTML::image('assets/images/logo-dark.png')!!}
                    </div>

                    @if(Session::has('failed'))
                        <h4 class="text-danger mt0">@lang("basic.whoops")! </h4>
                        <ul class="list-group">
                            <li class="list-group-item">@lang("User.login_fail_msg")</li>
                        </ul>
                    @endif

                    <div class="form-group">
                        {!! Form::label('email', trans("User.email"), ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'autofocus' => true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', trans("User.password"), ['class' => 'control-label']) !!}
                        (<a class="forgotPassword" href="{{route('forgotPassword')}}" tabindex="-1">@lang("User.forgot_password?")</a>)
                        {!! Form::password('password',  ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">@lang("User.login")</button>
                    </div>

                    @if(Utils::isAttendize())
                    <div class="signup">
                        <span>@lang("User.dont_have_account_button", ["url"=> route('showSignup')])</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
