@extends('layouts.master', ['title' => 'Home'])
@section('content')
	<div class="welcome">
		<h1>You have arrived.</h1>
	</div>
	<pre>
		{{ Auth::user() }}
	</pre>
@stop