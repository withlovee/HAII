@extends('layouts.master', ['title' => 'Dashboard'])
@section('content')
<div class="row">
	<div class="col-md-5">
		<img src="img/map.png" alt="">
	</div>
	<!-- /.col-md-4 -->
	<div class="col-md-7">
		<h3>Real-time Out-of-Range Value Detection<br><small>(นับตั้งแต่ 7.01 น. ของวันที่  {{ thai_date() }} {{ date('Y', getTime()) }} จนถึงปัจจุบัน)</small></h3>
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#water" role="tab" data-toggle="tab">สถานีน้ำ</a></li>
			<li><a href="#rain" role="tab" data-toggle="tab">สถานีฝน</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="water">
				<table class="monitor-table table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="60%">ชื่อสถานี</th>
							<th width="20%">เวลาล่าสุด</th>
							<th>จำนวนปัญหา</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($water_problems as $basin_name => $p2)
						<tr class="heading">
							<td colspan="4" class="text-center">{{ $basin_name }}</td>
						</tr>
						@foreach ($p2 as $problem)
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail" data-code="{{ $problem['code'] }}">{{ $problem['full_name'] }}</a></td>
							<td>{{ $problem['end_time'] }}</td>
							<td>{{ $problem['num'] }}</td>
						</tr>
						@endforeach
					@endforeach
					</tbody>
				</table>
<!-- 				<ul class="pagination">
					<li><a href="#">&laquo;</a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#">&raquo;</a></li>
				</ul> -->
			</div>
			<div class="tab-pane" id="rain">
				<table class="monitor-table table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="50%">ชื่อสถานี</th>
							<th>เวลา</th>
							<th>จำนวนปัญหา</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($rain_problems as $basin_name => $p2)
						<tr class="heading">
							<td colspan="4" class="text-center">{{ $basin_name }}</td>
						</tr>
						@foreach ($p2 as $problem)
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail" data-code="{{ $problem['code'] }}">{{ $problem['full_name'] }}</a></td>
							<td>{{ $problem['end_time'] }}</td>
							<td>{{ $problem['num'] }}</td>
						</tr>
						@endforeach
					@endforeach
					</tbody>
				</table>
<!-- 				<ul class="pagination">
					<li><a href="#">&laquo;</a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">&raquo;</a></li>
				</ul> -->
			</div>
			<!-- /.col-md-8 -->
		</div>
		<!-- /.tab-content -->
		<h3>รายงานของวันที่ {{ thai_date(getTime(-1)) }} {{ date('Y', getTime(-1)) }}</h3>
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
</div>
<p>&nbsp;</p>

<div class="modal fade" id="detail">
	<div class="modal-dialog large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">ข้อมูลระดับน้ำของสถานีสวี2 (SVI002)</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<dl class="dl-horizontal dl-space">
							<dt>สถานี</dt><dd>สวี2 (SVI002)</dd>
							<dt>ตำบล</dt><dd>นาสัก</dd>
							<dt>อำเภอ</dt><dd>สวี</dd>
							<dt>จังหวัด</dt><dd>ชุมพร</dd>
							<dt>ภูมิภาค</dt><dd>ใต้</dd>
							<dt>ลุ่มแม่น้ำ</dt><dd>แม่น้ำป่าสัก</dd>
						</dl>
					</div>
					<!-- /.col-md-6 -->
					<div class="col-md-9">
					<img src="img/graph.png" alt="">
						
					</div>
					<!-- /.col-md-9 -->
				</div>
				<!-- /.row -->
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop