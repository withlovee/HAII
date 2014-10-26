<table class="table">
	<thead>
		<tr>
			<td>ID</td>
			<td>วันเวลาเริ่ม</td>
			<td>รหัสสถานี</td>
			<td>ชื่อสถานี</td>
			<td>ประเภทของปัญหา</td>
			<td>จำนวน</td>
			<td>ใช่ปัญหา</td>
			<td>ไม่ใช่ปัญหา</td>
		</tr>
	</thead>
	<tbody>
		@include('errorlog/table_entry')
	</tbody>
</table>
<!-- /.table -->
<?php echo $problems->appends($params)->links(); ?>