{{ Form::open(array('method' => 'get', 'class' => 'form-inline filters')) }}
	<div class="form-group">
		<label for="">เลือกดูตาม</label>
	</div>
	<div class="form-group">
		{{ Form::select('basin', $basins, $params_water['basin'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('province', $provinces, $params_water['province'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('code', $codes, $params_water['code'], array('class' => 'form-control chosen')) }}
	</div>
	<div class="form-group">
		{{ Form::select('problem_type', array(
			'' => 'ปัญหาทุกประเภท',
			'BD' => 'Out-of-Range (BD)',
			'FV' => 'Flat Value (FV)',
			'MG' => 'Missing Gap (MG)',
			'OL' => 'Outlier (OL)',
			'HM' => 'Homogeneity (HM)',
			'MP' => 'Missing Pattern (MP)'
			), $params_water['problem_type'], array('class' => 'form-control chosen'))
		}}
	</div>
	<input type="hidden" name="data_type" value="WATER">
	<input type="hidden" name="marked" value="{{ $status }}">
	@if($selectDate)
	<p></p>
	<div class="form-inline">
		<div class="form-group">
			<label for="">ตั้งแต่</label>
			<input name="start_date" type="date" class="form-control" value="{{ $params_water['start_date'] }}">
			<input name="start_time" type="time" class="form-control" value="{{ $params_water['start_time'] }}">
		</div>
		<div class="form-group">
			<label for="">ถึง</label>
			<input name="end_date" type="date" class="form-control" value="{{ $params_water['end_date'] }}">
			<input name="end_time" type="time" class="form-control" value="{{ $params_water['end_time'] }}">
		</div>
		<button type="submit" class="query_btn btn btn-primary">Go</button>
	</div>
	@else
	<button type="submit" class="query_btn btn btn-primary">Go</button>
	@endif
{{ Form::close() }}