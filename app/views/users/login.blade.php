@extends('layouts.master', ['title' => 'Please Sign In'])

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
        <form method="POST" action="{{{ URL::to('/users/login') }}}" accept-charset="UTF-8" class="form-signin">
        <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.username_e_mail') }}}</label>
            <input class="form-control" tabindex="1" placeholder="{{{ Lang::get('confide::confide.username_e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
        </div>
        <div class="form-group">
            <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
            <a href="{{{ URL::to('/users/forgot_password') }}}">{{{ Lang::get('confide::confide.login.forgot_password') }}}</a>
            <input class="form-control" tabindex="2" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <label for="remember" class="checkbox">{{{ Lang::get('confide::confide.login.remember') }}}
                <input type="hidden" name="remember" value="0">
                <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
            </label>
        </div>
        <button tabindex="3" type="submit" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-ok"></span> {{{ Lang::get('confide::confide.login.submit') }}}</button>
        <br>
        </form>
    </div>
</div><!--row-->
@stop