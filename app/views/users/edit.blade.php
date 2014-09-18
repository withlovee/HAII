@extends('layouts.master', ['title' => 'Edit User'])
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
{{ Form::model($user, ['action' => ['UsersController@update', $user->id]]) }}
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    {{ Form::model($user, array('action' => array('UsersController@update', $user->id))) }}
    <fieldset>
        @include('users.form')
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

    </fieldset>
{{ Form::close() }}
@stop