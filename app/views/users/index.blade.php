@extends('layouts.master', ['title' => 'All Users'])
@section('header-buttons')
	<div class="btn-group right">
		{{ HTML::link('users/create', 'Add User', array('class' => 'btn btn-primary')) }}
	</div>
@stop
@section('content')
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Username</th>
				<th>E-mail</th>
				<th>Role</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($users as $user)
			<tr>
				<td>{{ $user->id }}</td>
				<td>{{ $user->username }}</td>
				<td>{{ $user->email }}</td>
				<td>{{ $user->role }}</td>
				<td>
					<a href="{{ action('UsersController@edit', [$user->id]) }}" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a href="{{ action('UsersController@destroy', [$user->id]) }}" class="btn btn-xs btn-danger" onclick="if(!confirm('Are you sure?')) event.preventDefault();"><i class="glyphicon glyphicon-trash"></i> Delete</a>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop