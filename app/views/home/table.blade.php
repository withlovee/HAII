
				<table class="monitor-table table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="60%">ชื่อสถานี</th>
							<th width="20%">เวลาล่าสุด</th>
							<th width="20%">จำนวนปัญหา</th>
						</tr>
					</thead>
					<tbody>
					@if(sizeof($table_data) == 0)
						<tr>
							<td colspan="4" class="text-center">ปัญหาของวันนี้ถูกจัดการเรียบร้อยแล้ว</td>
						</tr>
					@endif
					@foreach ($table_data as $basin_name => $p2)
						<tr class="heading">
							<td colspan="4" class="text-center">{{ $basin_name }}</td>
						</tr>
						@foreach ($p2 as $problem)
						<tr>
							<td><a href="" class="model_btn" data-toggle="modal" data-id="{{ $problem['id'] }}" data-target="#detail" data-code="{{ $problem['code'] }}">{{ $problem['full_name'] }}</a></td>
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