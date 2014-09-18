@extends('layouts.master', ['title' => 'Create a New User'])
@section('header-buttons')
    <div class="btn-group right">
        {{ HTML::link('users', 'กลับไปหน้ารวม', array('class' => 'btn btn-default')) }}
    </div>
@stop
@section('content')

@if (Session::get('error'))
    <div class="alert alert-danger">
        @if (is_array(Session::get('error')))
            {{ head(Session::get('error')) }}
        @endif
    </div>
@endif

@if (Session::get('notice'))
    <div class="alert alert-success">{{ Session::get('notice') }}</div>
@endif
<form method="POST" action="{{{ URL::to('users') }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        @include('users.form')
        <div class="form-group">
            <label for="role">Role</label>
            {{ Form::select('role', array('User' => 'User', 'Admin' => 'Admin'), null, ['class' => 'form-control']) }}
        </div>
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
        </div>

    </fieldset>
</form>
@stop