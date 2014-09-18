@extends('layouts.master', ['title' => 'Enter new password'])

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

        <form method="POST" action="{{{ URL::to('/users/reset_password') }}}" accept-charset="UTF-8">
            <input type="hidden" name="token" value="{{{ $token }}}">
            <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

            <div class="form-group">
                <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
                <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
                <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="form-actions form-group">
                <button type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.forgot.submit') }}}</button>
            </div>
        </form>
    </div>
</div>
@stop