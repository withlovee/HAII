@extends('layouts.master', ['title' => 'Edit User'])
@section('header-buttons')
    <div class="btn-group right">
        {{ HTML::link('users', 'Back', array('class' => 'btn btn-default')) }}
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
{{ Form::model($user, ['action' => ['UsersController@update', $user->id]]) }}
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
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>

    </fieldset>
{{ Form::close() }}
@stop