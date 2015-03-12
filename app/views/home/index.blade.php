@extends('layouts.master', ['title' => 'Dashboard'])
@section('content')
<div class="row">
	<div class="col-md-5">
		<div class="btn-group" data-toggle="buttons" id="map-selector">
			<label class="btn btn-default active">
		    <input type="radio" value="OR" name="map-selector" autocomplete="off" checked>OR
		  </label>
		  <label class="btn btn-default">
		    <input type="radio" value="MG" name="map-selector" autocomplete="off">MG
		  </label>
		  <label class="btn btn-default">
		    <input type="radio" value="FV" name="map-selector" autocomplete="off">FV
		  </label>
		  <label class="btn btn-default">
		    <input type="radio" value="OL" name="map-selector" autocomplete="off">OL
		  </label>
		  <label class="btn btn-default">
		    <input type="radio" value="HM" name="map-selector" autocomplete="off">HM
		  </label>
		  <label class="btn btn-default">
		    <input type="radio" value="MP" name="map-selector" autocomplete="off">MP
		  </label>
		</div>
		<div id="map-canvas" style="height: 700px"></div>
	</div>
	<!-- /.col-md-4 -->
	<div class="col-md-7">
		<div class="today-report">
			<h3>Out-of-Range Monitor<br><small>(นับตั้งแต่ 7.01 น. ของวันที่  {{ thai_date() }} {{ date('Y', getTime()) }} จนถึงปัจจุบัน)</small></h3>
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#water" role="tab" data-toggle="tab">ข้อมูลระดับน้ำ</a></li>
				<li><a href="#rain" role="tab" data-toggle="tab">ข้อมูลฝน</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="water">
					@include('home.table', array('table_data' => $water_problems))
				</div>
				<div class="tab-pane" id="rain">
					@include('home.table', array('table_data' => $rain_problems))
				</div>
				<!-- /.col-md-8 -->
			</div>
			<!-- /.tab-content -->
		</div>
		<!-- /.today-report -->

		<div class="yesterday-report">
			<h3>รายงานของวันที่ {{ thai_date(getTime(-1)) }} {{ date('Y', getTime(-1)) }} (เมื่อวาน)</h3>
			<div class="row">
				<div class="col-sm-6">
					<h4>ข้อมูลน้ำ</h4>
					<div class="stats">
						<div class="item">
							Out-of-Range
							<span class="num">{{ $stats['WATER']['OR'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Flat Value
							<span class="num">{{ $stats['WATER']['FV'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Missing Gap
							<span class="num">{{ $stats['WATER']['MG'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Outlier
							<span class="num">{{ $stats['WATER']['OL'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Homogeneity
							<span class="num">{{ $stats['WATER']['HM'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Missing Pattern
							<span class="num">{{ $stats['WATER']['MP'] }}</span>
						</div>
						<!-- /.item -->
					</div>
					<!-- /.stats -->
				</div>
				<!-- /.col-sm-6 -->
				<div class="col-sm-6">
					<h4>ข้อมูลฝน</h4>
					<div class="stats">
						<div class="item">
							Out-of-Range
							<span class="num">{{ $stats['RAIN']['OR'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item-disabled"></div>
						<div class="item">
							Missing Gap
							<span class="num">{{ $stats['RAIN']['MG'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item-disabled"></div>
						<div class="item-disabled"></div>
						<div class="item">
							Missing Pattern
							<span class="num">{{ $stats['RAIN']['MP'] }}</span>
						</div>
						<!-- /.item -->
					</div>
					<!-- /.stats -->
				</div>
				<!-- /.col-sm-6 -->
			</div>
			<!-- /.row -->

			
		</div>
		<!-- /.yesterday-report -->
	</div>
</div>
<p>&nbsp;</p>

<script>
$(document).ready(function(){
	console.log("script loaded")
	$('body').on('click', '.update', function(e){
		e.preventDefault();
		console.log('update status');
		el = $(this);
		data = {
			id: el.data('id'),
			status: el.data('error')
		}
		$.post("{{ URL::to('api/problems/update_status') }}", data)
			.done(function(res){
				console.log(res);
				if(res.success){
					el.parent().parent().find('a[data-id="'+data.id+'"]').removeClass('active');
					el.addClass('active');

					/* hotfix (bad practice) */
					$('tr[data-id="'+data.id+'"]').fadeOut(500);
					// el.parents('tr').fadeOut(400);
					// console.log(el.parents('tr'));
				}
			});
	});
});
</script>

@include('data_log/modal')
@include('home/map')

@stop
