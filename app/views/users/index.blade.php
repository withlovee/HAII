@extends('layouts.master', ['title' => 'All Users'])
@section('header-buttons')
	<div class="btn-group right">
		{{ HTML::link('users/create', 'เพิ่มผู้ใช้ใหม่', array('class' => 'btn btn-primary')) }}
	</div>
@stop
@section('content')
    @if (Session::get('notice'))
        <div class="alert alert-success">{{ Session::get('notice') }}</div>
    @endif
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
				<td></td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop