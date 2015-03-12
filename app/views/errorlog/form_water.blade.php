{{ Form::open(array('method' => 'get', 'class' => 'form-inline filters')) }}
	<div class="form-group">
		<label for="">เลือกดูตาม</label>
	</div>
	<div class="form-group">
		{{ Form::select('basin', $basins, $params_water['basin'], array('class' => 'form-control chosen', 'data-placeholder' => 'ลุ่มน้ำ')) }}
	</div>
	<div class="form-group">
		{{ Form::select('province', $provinces, $params_water['province'], array('class' => 'form-control chosen', 'data-placeholder' => 'จังหวัด')) }}
	</div>
	<div class="form-group">
		{{ Form::select('code', $codes, $params_water['code'], array('class' => 'form-control chosen', 'data-placeholder' => 'สถานี')) }}
	</div>
	<div class="form-group">
		{{ Form::select('problem_type', array(
			'' => '',
			'OR' => 'Out-of-Range (OR)',
			'FV' => 'Flat Value (FV)',
			'MG' => 'Missing Gap (MG)',
			'OL' => 'Outlier (OL)',
			'HM' => 'Homogeneity (HM)',
			'MP' => 'Missing Pattern (MP)'
			), $params_water['problem_type'], array('class' => 'form-control chosen', 'data-placeholder' => 'ปัญหาทุกประเภท'))
		}}
	</div>
	<input type="hidden" name="data_type" value="WATER">
	<input type="hidden" name="marked" value="{{ $status }}">
	<p></p>
	<div class="form-inline">
		<div class="form-group">
			<label for="">เรียงตาม</label>
			<div class="radio">
			  <label>
			  	{{ Form::radio('orderby', 'start_datetime', $params_water['orderby'] == 'start_datetime')}}
			    <!-- <input type="radio" name="orderby" id="orderbyRadio1" value="datetime"> -->
			    เวลาที่เกิดปัญหา
			  </label>
			  <label>
			  	{{ Form::radio('orderby', 'station_code', $params_water['orderby'] == 'station_code')}}
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
			<input name="start_date" type="text" class="form-control datepicker" value="{{ $params_water['start_date'] }}">
			<input name="start_time" type="text" class="form-control timepicker" value="{{ $params_water['start_time'] }}">
		</div>
		<div class="form-group">
			<label for="">ถึง</label>
			<input name="end_date" type="text" class="form-control datepicker" value="{{ $params_water['end_date'] }}">
			<input name="end_time" type="text" class="form-control timepicker" value="{{ $params_water['end_time'] }}">
		</div>
		<button type="submit" class="query_btn btn btn-primary">Go</button>
	</div>
	@else
	<button type="submit" class="query_btn btn btn-primary">Go</button>
	@endif
{{ Form::close() }}