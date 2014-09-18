@extends('layouts.master', ['title' => 'Edit User'])
@section('header-buttons')
    <div class="btn-group right">
        {{ HTML::link('users', 'Back', array('class' => 'btn btn-default')) }}
    </div>
@stop

@section('content')
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