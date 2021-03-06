<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ $title }} - Data Quality Management</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	{{ HTML::style('css/bootstrap.css'); }}
	{{ HTML::style('css/animate.min.css'); }}
	{{ HTML::style('css/bootstrap-datetimepicker.min.css'); }}
	{{ HTML::style('css/jquery-ui-1.10.4.custom.min.css'); }}
	{{ HTML::style('css/chosen.min.css'); }}
	<link rel="stylesheet/less" type="text/css" href="{{ URL::asset('css/style.less'); }}" />
	{{ HTML::script('js/jquery.min.js'); }}
	{{ HTML::script('js/jquery-ui-1.10.4.custom.min.js'); }}
	{{ HTML::script('js/jquery-ui-timepicker-addon.js'); }}
	{{ HTML::script('js/bootstrap.min.js'); }}
	{{ HTML::script('js/chosen.jquery.min.js'); }}
	{{ HTML::script('js/less.js'); }}
	<!--[if lt IE 9]>
		{{ HTML::script('js/html5shiv.js'); }}
		{{ HTML::script('js/respond.min.js'); }}
	<![endif]-->
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
	<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		{{ HTML::link('/', 'Data Quality Management', ['class' => 'navbar-brand']) }}
	</div>
	
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<li>{{ HTML::link('dailyop', 'Daily Operations') }}</li>
			<li>{{ HTML::link('errorlog', 'Error Log') }}</li>
			<li>{{ HTML::link('batch', 'Batch Processor') }}</li>
			<!-- 
			<li><a href="reports.php">Report Generation</a></li>
			<li><a href="settings.php">Settings</a></li>
			<li><a href="export.php">Export</a></li>
			-->
		</ul>
		@if(!Auth::check())
		<ul class="nav navbar-nav navbar-right">
			<li>{{ HTML::link('login', 'Login') }}</li>
		</ul>
		@else
		<ul class="nav navbar-nav navbar-right">
			@if(Auth::user()->role == 'Admin')
			<li>{{ HTML::link('users', 'Manage Users') }}</li>
			@endif
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello, {{ Auth::user()->username }} <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li>{{ HTML::link('users/profile', 'Edit Profile') }}</li>
					<li class="divider"></li>
					<li>{{ HTML::link('users/logout', 'Logout') }}</li>
				</ul>
			</li>
		</ul>
		@endif
	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<section class="content-wrapper">
	<div class="container">
	<div class="page-header">
		<h1 class="left">
			@section('page-title')
			{{ $title }}
			@show
		</h1>
		@yield('header-buttons')
		<div class="clear"></div>
	</div>
	<!--/.page-header -->
		@section('error')
			@if (Session::get('error'))
				<div class="alert alert-danger">
					@if (is_array(Session::get('error')))
						{{ head(Session::get('error')) }}
					@else
						{{{ Session::get('error') }}}
					@endif
				</div>
			@endif
			@if (Session::get('notice'))
				<div class="alert alert-success">{{ Session::get('notice') }}</div>
			@endif
		@show

		<!-- error message -->
		@if(Session::has('alert-success'))
			<div class="alert alert-success" role="alert">{{ Session::get('alert-success') }}</div>
		@endif
		@if(Session::has('alert-info'))
			<div class="alert alert-info" role="alert">{{ Session::get('alert-info') }}</div>
		@endif
		@if(Session::has('alert-warning'))
			<div class="alert alert-warning" role="alert">{{ Session::get('alert-warning') }}</div>
		@endif
		@if(Session::has('alert-danger'))
			<div class="alert alert-danger" role="alert">{{ Session::get('alert-danger') }}</div>
		@endif

		@yield('content')

	</div>
	<!-- /.container -->
</section>
<!-- /.content-wrapper -->
	<script>
		$(function(){

			$(".chosen").chosen();
			
		});
	</script>
	@yield('script')
</body>
</html>