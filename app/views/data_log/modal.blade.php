
{{ HTML::script('js/highstock.js'); }}
{{ HTML::script('js/underscore-min.js'); }}
<script>
$(function(){
	var modalTitle = $('.modal-title');
	var modalBody = $('.modal-body');
	var stationInfo = $('#station_info');
	var errorButtons = $('.modal-buttons');
	var startDateTimeHeader = $("#start_datetime");
	var endDateTimeHeader = $("#end_datetime");
	var problemTypeHeader = $("#problem_type");
	$('body').on('click', '.model_btn', function(e){
		id = $(this).data('id');
		modalTitle.html('กำลังโหลด');
		stationInfo.hide();
		modalBody.hide();
		errorButtons.hide();
		$.get("{{ URL::to('api/problems/get_problem') }}", {id: id})
		.done(function(res){
			/* Render Station Name/Code in Modal Header */
			modalTitle.html('ข้อมูลของสถานี'+res.station.name+' ('+res.station.code+')');

			startDateTimeHeader.html(res.start_datetime)
			endDateTimeHeader.html(res.end_datetime)
			problemTypeHeader.html(res.problem_type)

			modalBody.show(0, function(){
				/* Render Station Information in Modal */
				$.get("{{ URL::to('api/problems/render_station_info') }}", {station: res.station})
				.done(function(html){
					stationInfo.html(html).show(100);
				});
				$.get("{{ URL::to('api/problems/get_buttons') }}", {id: id})
				.done(function(html){
					errorButtons.html(html).show(100);
				});
				console.log(res);
				/* Render Charts */
				Highcharts.setOptions({
					global: {
						timezoneOffset: -7 * 60
					}
				});


				$.get("{{ URL::to('api/telestation/wldetail') }}", {station: res.station.code})
					.done(function(telewldetail){
					console.log(telewldetail);

					series = []

					// data
					series.push({
								name : "Value",
								id : "dataseries",
								data : res.data,
								step: false,
								tooltip: {
									valueDecimals: 2
								},
								marker: {
									enabled: true,
									radius: 4,
									symbol: 'diamond'
								},
								zIndex: 9
						});

					// flags
					series.push({
						type: 'flags',
						name: 'Flags on series',
						data: [{
							x: res.start_datetime_unix * 1000,
							title: 'เริ่ม'
						}, {
							x: res.end_datetime_unix * 1000,
							title: 'สิ้นสุด'
						}],
						onSeries: 'dataseries',
						shape: 'squarepin',
						zIndex:8
					});

					var maxPair = _.max(res.data, function(x) { return x[1]; });
					var maxVal = maxPair[1];

					var minPair = _.min(res.data, function(x) { return x[1]; });
					var minVal = minPair[1];

					if(res.problem_type == "OR" && res.data_type == "WATER") {
						groundLevel = parseInt(telewldetail.ground_level);
						leftBank = parseInt(telewldetail.left_bank);
						rightBank = parseInt(telewldetail.right_bank);
						maxBank = leftBank > rightBank ? leftBank : rightBank;

						groundLevelSeries = $.map(res.data, function(x, i){
							return [[x[0], groundLevel]];
						});

						maxBankSeries = $.map(res.data, function(x, i){
							return [[x[0], maxBank]];
						});

						series.push({
							name : "Ground Level",
							id: "groundlevel",
							data: groundLevelSeries,
							zIndex:5
						});
						series.push({
								name : "Max Bank",
								id: "maxBank",
								data: maxBankSeries,
								zIndex:5
						});

						// maxVal = _.max([maxVal, groundLevel, leftBank, rightBank]);

					}

					if (res.problem_type == "OR" && res.data_type == "RAIN"){
						rainThresholdSeries = $.map(res.data, function(x, i){
							return [[x[0], 120]];
						});
						series.push({
							name : "Rain Threshold",
							id: "rainThreshold",
							data: rainThresholdSeries,
							zIndex:5
						});

						// maxVal = _.max([maxVal, rainThreshold]);
					}


					series.push({
						type: 'area',
						name: 'Problem Area',
						threshold: minVal - 1,
						data: [[res.start_datetime_unix * 1000, maxVal + 1], [res.end_datetime_unix * 1000, maxVal + 1]],
						zIndex:1,
						lineWidth: 5,
						color: "rgba(255,200,200,0.5)"
					});
				
						$('#highcharts2').highcharts('StockChart', {
							rangeSelector : {
								selected : 1
							},
							legend : {
								enabled: true
							},
							title : {
								text : res.station.name
							},
							series : series
						});

				});

			});
		});
	});
});
</script>
<div class="modal fade" id="detail">
	<div class="modal-dialog large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">ข้อมูลระดับน้ำของสถานี<span id="station_name"></span> (<span id="station_code"></span>)</h4>
				<p>เกิดปัญหา <span id="problem_type"></span> ที่ <span id="start_datetime"></span> &#8212; <span id="end_datetime"></span></p>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<dl class="dl-horizontal dl-space" id="station_info">
						</dl>
						<p>&nbsp;</p>
						<h4>สถานะของปัญหา</h4>
						<div class="modal-buttons"></div>
					</div>
					<!-- /.col-md-6 -->
					<div class="col-md-9">
						<div id="highcharts2" style="height: 400px; min-width: 710px"></div>
						
					</div>
					<!-- /.col-md-9 -->
				</div>
				<!-- /.row -->
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->