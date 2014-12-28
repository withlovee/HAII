<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>[QC.HAII] {{ $num }} Daily Report</h2>

		@if(sizeof($rain) > 0)
		<h3>HAII Rainfall</h3>
		<dl>
			@foreach ($rain as $problem)
			<dt><strong>{{ $problem['name'] }}</strong>: {{ sizeof($problem['stations']) }} station(s)</dt>
			<dd style="margin-bottom: 10px">{{ implode(", ", $problem['stations']) }}</dd>
			@endforeach
		</dl>
		@endif

		@if(sizeof($water) > 0)
		<h3>HAII Waterlevel</h3>
			@foreach ($water as $problem)
			<dt><strong>{{ $problem['name'] }}</strong>: {{ sizeof($problem['stations']) }} station(s)</dt>
			<dd style="margin-bottom: 10px">{{ implode(", ", $problem['stations']) }}</dd>
			@endforeach
		@endif

		<p><strong>Last Checked:</strong> {{ $date }}</p>
		<p><a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a></p>
	</body>
</html>
