@extends('layouts.master', ['title' => 'Edit Profile'])

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
{{ Form::model($user, array('action' => array('UsersController@doProfile', $user->id))) }}
    <fieldset>
        @include('users.form')
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

    </fieldset>
{{ Form::close() }}
@stop