@extends('layouts.master', ['title' => 'Create a New User'])
@section('header-buttons')
    <div class="btn-group right">
        {{ HTML::link('users', 'Back', array('class' => 'btn btn-default')) }}
    </div>
@stop

@section('content')
<form method="POST" action="{{{ URL::to('users') }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset class="form-horizontal">
        @include('users.form')
        <div class="form-group">
            <label for="role" class="col-sm-2 control-label">Role</label>
            <div class="col-sm-3">
                {{ Form::select('role', array('User' => 'User', 'Admin' => 'Admin'), null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
            </div>
        </div>

    </fieldset>
</form>
@stop