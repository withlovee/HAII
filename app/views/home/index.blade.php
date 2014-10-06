@extends('layouts.master', ['title' => 'Dashboard'])
@section('content')
<div class="row">
	<div class="col-md-5">
		<img src="img/map.png" alt="">
	</div>
	<!-- /.col-md-4 -->
	<div class="col-md-7">
	<pre>
<?php 
    // $problems->each(function($p)
    // {
    //     var_dump($p->station_code);
    // });
var_dump($problems) 
// foreach($problems as $p){
// 	var_dump($p);
// }
?>
</pre>
		<h3>Real-time Out-of-Range Value Detection<br><small>(นับตั้งแต่ 7.01 น. ของวันที่ 14 กันยายน 2557 จนถึงปัจจุบัน)</small></h3>
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
						<tr class="heading">
							<td colspan="4" class="text-center">ลุ่มน้ำปิง</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">สามเงา ต. วังหมัน อ. สามเงา จ. ตาก</a></td>
							<td>21:10</td>
							<td>19</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">สันทราย ต. แม่แฝก อ. สันทราย จ. เชียงใหม่</a></td>
							<td>20:00</td>
							<td>18</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">เมืองลำปาง ต. ต้นธงชัย อ. เมืองลำปาง จ. ลำปาง</a></td>
							<td>21:00</td>
							<td>18</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">ฮอด ต. หางดง อ. ฮอด จ. เชียงใหม่</a></td>
							<td>19:50</td>
							<td>5</td>
						</tr>
						<tr class="heading">
							<td colspan="4" class="text-center">ลุ่มน้ำสาละวิน</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">สวี2 ต.นาสัก อ.สวี จ.ชุมพร</a></td>
							<td>21:00</td>
							<td>25</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">เก้าเลี้ยว ต. หัวดง อ. เก้าเลี้ยว จ. นครสวรรค์</a></td>
							<td>21:00</td>
							<td>12</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">สามเงา ต. วังหมัน อ. สามเงา จ. ตาก</a></td>
							<td>21:10</td>
							<td>2</td>
						</tr>
					</tbody>
				</table>
				<ul class="pagination">
					<li><a href="#">&laquo;</a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#">&raquo;</a></li>
				</ul>
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
						<tr class="heading">
							<td colspan="4" class="text-center">ลุ่มน้ำปิง</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">เมืองลำปาง ต. ต้นธงชัย อ. เมืองลำปาง จ. ลำปาง</a></td>
							<td>21:00</td>
							<td>20</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">ฮอด ต. หางดง อ. ฮอด จ. เชียงใหม่</a></td>
							<td>19:50</td>
							<td>18</td>
						</tr>
						<tr class="heading">
							<td colspan="4" class="text-center">ลุ่มน้ำสาละวิน</td>
						</tr>
						<tr>
							<td><a href="" data-toggle="modal" data-target="#detail">สวี2 ต.นาสัก อ.สวี จ.ชุมพร</a></td>
							<td>21:00</td>
							<td>15</td>
						</tr>
					</tbody>
				</table>
				<ul class="pagination">
					<li><a href="#">&laquo;</a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">&raquo;</a></li>
				</ul>
			</div>
			<!-- /.col-md-8 -->
		</div>
		<!-- /.tab-content -->
		<h3>รายงานของวันที่ 14 กันยายน 2557 (เมื่อวาน)</h3>
		<div class="row">
			<div class="col-sm-6">
				<h4>ข้อมูลน้ำ</h4>
				<img src="img/dashboard2.png" class="img-responsive" alt="">
			</div>
			<!-- /.col-sm-6 -->
			<div class="col-sm-6">
				<h4>ข้อมูลฝน</h4>
				<img src="img/dashboard2.png" class="img-responsive" alt="">
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