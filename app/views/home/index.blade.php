@extends('layouts.master', ['title' => 'Dashboard'])
@section('content')
<div class="row">
	<div class="col-md-5">
		<!-- @include('home/map') -->
	</div>
	<!-- /.col-md-4 -->
	<div class="col-md-7">
		<div class="today-report">
			<h3>Out-of-Range Monitor<br><small>(นับตั้งแต่ 7.01 น. ของวันที่  {{ thai_date() }} {{ date('Y', getTime()) }} จนถึงปัจจุบัน)</small></h3>
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#water" role="tab" data-toggle="tab">สถานีน้ำ</a></li>
				<li><a href="#rain" role="tab" data-toggle="tab">สถานีฝน</a></li>
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
							Out-of-value
							<span class="num">{{ $stats['WATER']['BD'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Flat Value
							<span class="num">0</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Missing Gap
							<span class="num">0</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Outlier
							<span class="num">0</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Homogeneity
							<span class="num">0</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Missing Pattern
							<span class="num">0</span>
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
							Out-of-value
							<span class="num">{{ $stats['RAIN']['BD'] }}</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Flat Value
							<span class="num">0</span>
						</div>
						<!-- /.item -->
						<div class="item">
							Missing Gap
							<span class="num">0</span>
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

@include('data_log/modal')


@stop
