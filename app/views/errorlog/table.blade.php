<table class="table">
	<thead>
		<tr>
			<!-- <td>ID</td> -->
			<td>เริ่ม</td>
			<td>สิ้นสุด</td>
			<td>รหัสสถานี</td>
			<td>ชื่อสถานี</td>
			<td>ประเภทของปัญหา</td>
			<td>จำนวน</td>
			@if( isAdmin() )
				<td>ปัญหา</td>
				<td>ไม่ใช่ปัญหา</td>
				<td>ไม่ระบุ</td>
			@endif
		</tr>
	</thead>
	<tbody>
		@include('errorlog/table_entry')
	</tbody>
</table>
<!-- /.table -->
<?php echo $problems->appends($params)->links(); ?>