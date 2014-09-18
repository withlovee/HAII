@extends('layouts.master', ['title' => 'Edit Profile'])

@section('content')
{{ Form::model($user, array('action' => array('UsersController@doProfile', $user->id))) }}
    <fieldset class="form-horizontal">
        @include('users.form')
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>

    </fieldset>
{{ Form::close() }}
@stop