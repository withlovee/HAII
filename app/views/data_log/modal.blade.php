
{{ HTML::script('js/highstock.js'); }}
<script>
$(function(){
	var modalTitle = $('.modal-title');
	var modalBody = $('.modal-body');
	var stationInfo = $('#station_info');
	var errorButtons = $('.modal-buttons');
	$('body').on('click', '.model_btn', function(e){
		id = $(this).data('id');
		modalTitle.html('กำลังโหลด');
		stationInfo.hide();
		modalBody.hide();
		errorButtons.hide();
		$.get("{{ URL::to('api/problems/get_problem') }}", {id: id})
		.done(function(res){
			/* Render Station Name/Code in Modal Header */
			modalTitle.html('ข้อมูลระดับน้ำของสถานี'+res.station.name+' ('+res.station.code+')');
			modalBody.show(0, function(){
				/* Render Station Information in Modal */
				$.get("{{ URL::to('api/problems/render_station_info') }}", {station: res.station})
				.done(function(html){
					stationInfo.html(html).show(100);
					// modalBody.css('height', 'auto');
				});
				$.get("{{ URL::to('api/problems/get_buttons') }}", {id: id})
				.done(function(html){
					errorButtons.html(html).show(100);
					// modalBody.css('height', 'auto');
				});

				/* Render Charts */
				$('#highcharts').highcharts('StockChart', {
					global: {
						useUTC: false
					},
					rangeSelector : {
						selected : 1
					},
				legend : {
					enabled: true
				},
					title : {
						text : res.station.name
					},
					series : [{
						name : "Value",
						id : "dataseries",
						data : res.data,
						step: true,
						tooltip: {
							valueDecimals: 2
						}
					},
					{
						type: 'flags',
						name: 'Flags on series',
						data: [{
							x: res.start_datetime_unix*1000,
							title: 'เริ่ม'
						}, {
							x: res.end_datetime_unix*1000,
							title: 'สิ้นสุด'
						}],
						onSeries: 'dataseries',
						shape: 'squarepin'
					}
					]
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
						<div id="highcharts" style="height: 400px; min-width: 710px"></div>
						
					</div>
					<!-- /.col-md-9 -->
				</div>
				<!-- /.row -->
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->