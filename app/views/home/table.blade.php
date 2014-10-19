
				<table class="home-table monitor-table table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="60%">ชื่อสถานี</th>
							<th width="15%">เวลาล่าสุด</th>
							<th width="15%">จำนวน</th>
							<th width="10%"></th>
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
							<td>
								<a href="#" data-error="true" data-id="{{ $problem['id'] }}" class="update"><span class="glyphicon glyphicon-ok"></span></a>
								<a href="#" data-error="false" data-id="{{ $problem['id'] }}" class="update"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
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
<script>
$('.monitor-table').on('click', '.update', function(e){
	e.preventDefault();
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
				el.parents('tr').fadeOut(400);
				// console.log(el.parents('tr'));
			}
		});
});
</script>