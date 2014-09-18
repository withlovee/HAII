@extends('layouts.master', ['title' => 'Forgot Password'])
@section('header-buttons')
    <div class="btn-group right">
        {{ HTML::link('login', 'Back to Login', array('class' => 'btn btn-default')) }}
    </div>
@stop

@section('error')
@stop

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4" id="signin">
            @if (Session::get('error'))
                <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
            @endif
            @if (Session::get('notice'))
                <div class="alert alert-success">{{ Session::get('notice') }}</div>
            @endif
            <form method="POST" action="{{ URL::to('/users/forgot_password') }}" accept-charset="UTF-8">
                <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

                <div class="form-group">
                    <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
                    <div class="input-append input-group">
                        <input class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
                        <span class="input-group-btn">
                            <input class="btn btn-primary" type="submit" value="{{{ Lang::get('confide::confide.forgot.submit') }}}">
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop