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
	<li class="{{ $water }}"><a href="water">สถานีน้ำ</a></li>
	<li class="{{ $rain }}"><a href="rain">สถานีฝน</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade in active">
		@include('errorlog/form_'.$data_type)
		<div id="div1" class="table-full monitor-table" style="width:100%">
			@include('errorlog/table')
		</div>
	</div>
</div>
{{ HTML::style('css/chosen.min.css'); }}
{{ HTML::script('js/chosen.jquery.min.js'); }}
{{ HTML::style('css/watable.css'); }}
{{ HTML::script('js/jquery.watable.js'); }}
<script>
$(document).ready(function() {
	function HAIIWATable(divName, params){
		mainElement = $(divName);
		args = {};
		function getFormObj(inputs) {
			var formObj = {};
			$.each(inputs, function (i, input) {
				formObj[input.name] = input.value;
			});
			return formObj;
		}
		// function getTable(){
		// 	$.get("{{ URL::to('api/problems/get_table') }}", args)
		// 		.done(function(data) {
		// 		console.log(data);
		// 		$("#"+args.data_type+" .table-full").html(data);
		// 	});
		// }
		// mainElement.parent().find('form').on('click', '.query_btn', function(e){
		// 	e.preventDefault();
		// 	args = getFormObj($(this).parents('form').serializeArray());
		// 	console.log(args);
		// 	getTable();
		// });
		mainElement.parent().on('click', '.pagination a', function(e){
			// e.preventDefault();
			// args = getFormObj($(this).parents('form').serializeArray());
			// // args.page = $(this).html();
			// console.log($(this).parents('form').serializeArray());
			// console.log(args);
			// getTable();
		});
	}
	/* -- Clicking status buttons (Error/ Not Error) -- */
	$('body').on('click', '.update', function(e){
		e.preventDefault();
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
	new HAIIWATable("#div1", {
		data_type: 'WATER', 
		marked: '{{ $status }}',
		start_date: '{{ $start_date }}',
		start_time: '{{ $start_time }}'
	});
	new HAIIWATable("#div2", {
		data_type: 'RAIN', 
		marked: '{{ $status }}',
		start_date: '{{ $start_date }}',
		start_time: '{{ $start_time }}'
	});
});
</script>
@include('data_log/modal')

@stop