<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Report</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td style="padding: 20px 0;">
				<table border="0" cellpadding="0" cellspacing="0" style="width: 600px; margin: 0 auto;">
					
					<!-- Header -->
					<tr style="background: #3498db; font-size: 24px;">
						<td>
							<h2 style="padding: 5px 20px; margin: 0; color: #f0f0f0;">[QC.HAII] {{ $reportName }}</h2>
							<h3 style="padding: 5px 20px; margin: 0; color: #ccc;">{{ $startdate }} - {{ $enddate }}</h3>
						</td>
					</tr>
					<!-- End Header -->

					<!-- water data table -->
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<h3>
											ข้อมูลระดับน้ำ
										</h3>
									</td>
								</tr>
								<tr>
									<td>
										<table border="1" cellpadding="0" cellspacing="0" width="100%">
											<tr style="background: #16a085; color: white; font-size: 18px; text-align: center;">
												<td width="20%" >ปัญหา</td>
												<td width="10%">จำนวน</td>
												<td width="70%">รายชื่อสถานี</td>
											</tr>
											@foreach($water as $w)
												<tr>
													<td>{{ getProblemName($w['name']) }}</td>
													<td>{{ count($w['stations']) }}</td>
													<td>{{ implode(', ', $w['stations'])}}</td>
												</tr>
											@endforeach
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- end of water data table -->

					<!-- rain data table -->
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<h3>
											ข้อมูลระดับน้ำฝน
										</h3>
									</td>
								</tr>
								<tr>
									<td>
										<table border="1" cellpadding="0" cellspacing="0" width="100%">
											<tr style="background: #16a085; color: white; font-size: 18px; text-align: center;">
												<td width="20%" >ปัญหา</td>
												<td width="10%">จำนวน</td>
												<td width="70%">รายชื่อสถานี</td>
											</tr>
											@foreach($rain as $r)
												<tr>
													<td>{{ getProblemName($r['name']) }}</td>
													<td>{{ count($r['stations']) }}</td>
													<td>{{ implode(', ', $r['stations'])}}</td>
												</tr>
											@endforeach
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- end of rain data table -->


				</table>
			</td>
		</tr>
	</table>
</body>
</html>