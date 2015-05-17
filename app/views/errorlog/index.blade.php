@extends('layouts.master', ['title' => $title])
@section('header-buttons')
	<div class="btn-group right">
		<a href="../all/{{ $data_type }}" class="btn btn-default {{ $all }}">ดูปัญหาทั้งหมด</a>
		<a href="../marked/{{ $data_type }}" class="btn btn-default {{ $marked }}">ดูปัญหาที่แก้ไขแล้ว</a>
		<a href="../unmarked/{{ $data_type }}" class="btn btn-default {{ $unmarked }}">ดูปัญหาที่ยังไม่แก้ไข</a>
	</div>
@stop

@section('content')
<ul class="nav nav-tabs">
	<li class="{{ $water }}"><a href="water">ข้อมูลระดับน้ำ</a></li>
	<li class="{{ $rain }}"><a href="rain">ข้อมูลฝน</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade in active">
		@include('errorlog/form_'.$data_type)
		<div id="div1" class="table-full monitor-table" style="width:100%">
			@include('errorlog/table')
		</div>
	</div>
</div>
{{--
{{ HTML::style('css/chosen.min.css'); }}
{{ HTML::script('js/chosen.jquery.min.js'); }}
{{ HTML::style('css/watable.css'); }}
{{ HTML::script('js/jquery.watable.js'); }}
--}}
<script>
$(document).ready(function() {
=
	/* -- Clicking status buttons (Error/ Not Error) -- */
	$('body').on('click', '.update', function(e){
		e.preventDefault();
		console.log("update button clicked");
		el = $(this);
		data = {
			id: el.data('id'),
			status: el.data('error')
		}
		if(el.hasClass('active'))
			data.status = 'undefined';
		$.post("{{ URL::to('api/problems/update_status') }}", data)
			.done(function(res){
				console.log(res);
				if(res.success){
					$('a[data-id="'+data.id+'"]').removeClass('active');
					console.log('a[data-id="'+data.id+'" data-error="'+data.status+'"]');
					$('a[data-error="'+data.status+'"][data-id="'+data.id+'"]').addClass('active');
				}
			});
	});
=
	/*----- Update provice, stationcode dropdown -----*/

	$('select[name="province"]').chosen({
		allow_single_deselect: true
	});

	$('select[name="basin"]').chosen({
		allow_single_deselect: true
	});

	$('select[name="code"]').chosen({
		allow_single_deselect: true
	});

	$('select[name="problem_type"]').chosen({
		allow_single_deselect: true
	});


	var updateProvince = function(basin) {
		console.log("update province with basin:"+basin);
		var url = "{{ URL::to('api/telestation/basin/province') }}";
		var data = {basin: basin};

		var province = [];
		$.post(url, data).done(function(res){
			province = res;

			setProvince(province);
			updateStation(province);
		});
		
	}

	var setProvince = function(province) {

		dropdown = $('select[name="province"]');
		oldValue = dropdown.val();

		dropdown.html("");
		dropdown.append('<option value=""></option>');

		for(i = 0; i < province.length;i++) {
			if (province[i] == oldValue) {
				dropdown.append('<option value="'+province[i]+'" selected="selected">'+province[i]+'</option>');	
			} else {
				dropdown.append('<option value="'+province[i]+'">'+province[i]+'</option>');
			}
			
		}

		dropdown.trigger("chosen:updated");

	}

	var updateStation = function(province) {
		provinceDropdown = $('select[name="province"]');
		if (provinceDropdown.val() != "") {
			province = provinceDropdown.val();
		}

		console.log("update station with province:"+province);

		var url = "{{ URL::to('api/telestation/province/station') }}";
		var data = {province: province};

		var station = [];
		$.post(url, data).done(function(res){
			station = res;
			setStation(station);
		});
	}

	var setStation = function(station) {
		dropdown = $('select[name="code"]');
		oldValue = dropdown.val();

		dropdown.html("");
		dropdown.append('<option value=""></option>');

		for(i = 0; i < station.length;i++) {
			if (station[i] == oldValue) {
				dropdown.append('<option value="'+station[i]+'" selected="selected">'+station[i]+'</option>');	
			} else {
				dropdown.append('<option value="'+station[i]+'">'+station[i]+'</option>');	
			}
			
		}

		dropdown.trigger("chosen:updated");
	}

	$('select[name="basin"]').change(function(){
		var basin = this.value;
		if(!basin) {
			basin = "all"
		}
		updateProvince(basin);
	});

	$('select[name="province"]').change(function(){
		var province = this.value;
		if(!province) {
			province = "all"
		}
		updateStation(province);
	});

	var initProvinceAndStationFilter = function() {
		// basin + province 
		var basinDropdown = $('select[name="basin"]');
		basinDropdown.trigger('change');
	}

	initProvinceAndStationFilter();

	$(".datepicker").datepicker({
		dateFormat: "yy-mm-dd",
		// minDate: "+1d",
		dayNamesMin: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
		monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" ]
	});
	$('.timepicker').timepicker({
		timeFormat: "HH:mm",
		pickerTimeFormat: "HH:mm"
	});

});
</script>
@include('data_log/modal')

@stop