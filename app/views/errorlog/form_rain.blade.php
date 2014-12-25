{{ Form::open(array('method' => 'get', 'class' => 'form-inline filters')) }}
<form class="form-inline filters" role="form">
	<div class="form-group">
		<label for="">เลือกดูตาม</label>
	</div>
	<div class="form-group">
		{{ Form::select('basin', $basins, $params_rain['basin'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('part', $parts, $params_rain['part'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('province', $provinces, $params_rain['province'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('code', $codes, $params_rain['code'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('problem_type', array(
			'' => 'ปัญหาทุกประเภท',
			'OR' => 'Out-of-Range (OR)',
			'FV' => 'Flat Value (FV)',
			'MG' => 'Missing Gap (MG)'
			), $params_rain['problem_type'], array('class' => 'form-control chosen'))
		}}
	</div>	
	<input type="hidden" name="data_type" value="RAIN">
	<input type="hidden" name="marked" value="{{ $status }}">
	<p></p>
	<div class="form-inline">
		<div class="form-group">
			<label for="">เรียงตาม</label>
			<div class="radio">
			  <label>
			  	{{ Form::radio('orderby', 'start_datetime', $params_rain['orderby'] == 'start_datetime')}}
			    <!-- <input type="radio" name="orderby" id="orderbyRadio1" value="datetime"> -->
			    เวลาที่เกิดปัญหา
			  </label>
			  <label>
			  	{{ Form::radio('orderby', 'station_code', $params_rain['orderby'] == 'station_code')}}
			    <!-- <input type="radio" name="orderby" id="orderbyRadio2" value="stationcode"> -->
			    รหัสสถานี
			  </label>
			</div>
		</div>
	</div>
	@if($selectDate)
	<p></p>
	<div class="form-inline">
		<div class="form-group">
			<label for="">ตั้งแต่</label>
			<input name="start_date" type="date" class="form-control" value="{{ $params_rain['start_date'] }}">
			<input name="start_time" type="time" class="form-control" value="{{ $params_rain['start_time'] }}">
		</div>
		<div class="form-group">
			<label for="">ถึง</label>
			<input name="end_date" type="date" class="form-control" value="{{ $params_rain['end_date'] }}">
			<input name="end_time" type="time" class="form-control" value="{{ $params_rain['end_time'] }}">
		</div>
		<button type="submit" class="query_btn btn btn-primary">Go</button>
	</div>
	@else
	<button type="submit" class="query_btn btn btn-primary">Go</button>
	@endif
{{ Form::close() }}