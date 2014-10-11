@extends('layouts.master', ['title' => 'Error Log'])
@section('header-buttons')
	<div class="btn-group right">
		<a href="{{ URL::to('errorlog/marked') }}" class="btn btn-default {{ $marked }}">ดูปัญหาที่แก้ไขแล้ว</a>
		<a href="{{ URL::to('errorlog/unmarked') }}" class="btn btn-default {{ $unmarked }}">ดูปัญหาที่ยังไม่แก้ไข</a>
	</div>
@stop

@section('content')
<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="#water" role="tab" data-toggle="tab">สถานีน้ำ</a></li>
	<li><a href="#rain" role="tab" data-toggle="tab">สถานีฝน</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="water">
		<form class="form-inline filters" role="form">
			<div class="form-group">
				<label for="">เลือกดูตาม</label>
			</div>
			<div class="form-group">
				{{ Form::select('basin', $basins, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::select('province', $provinces, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::select('code', $codes, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				<select name="problem_type" id="problem_type" class="form-control">
					<option value="">ปัญหาทุกประเภท</option>
					<option value="BD">Out-of-Range (BD)</option>
					<option value="FV">Flat Value (FV)</option>
					<option value="MG">Missing Gap (MG)</option>
					<option value="OL">Outlier (OL)</option>
					<option value="HM">Homogeneity (HM)</option>
					<option value="MP">Missing Pattern (MP)</option>
				</select>
			</div>
			<input type="hidden" name="data_type" value="WATER">
			<input type="hidden" name="marked" value="{{ $status }}">
			<p></p>
			<div class="form-inline">
				<div class="form-group">
					<label for="">ตั้งแต่</label>
					<input name="start_date" type="date" class="form-control">
					<input name="start_time" type="time" class="form-control">
				</div>
				<div class="form-group">
					<label for="">ถึง</label>
					<input name="end_date" type="date" class="form-control">
					<input name="end_time" type="time" class="form-control">
				</div>
				<button type="submit" class="query_btn btn btn-primary">Go</button>
			</div>
		</form>
		<div id="div1" class="table-full monitor-table" style="width:100%"></div>
	</div>
	<div class="tab-pane" id="rain">
		<form class="form-inline filters" role="form">
			<div class="form-group">
				<label for="">เลือกดูตาม</label>
			</div>
			<div class="form-group">
				{{ Form::select('basin', $basins, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::select('part', $parts, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::select('province', $provinces, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::select('code', $codes, null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				<select name="problem_type" id="problem_type" class="form-control">
					<option value="">ปัญหาทุกประเภท</option>
					<option value="BD">Out-of-Range (BD)</option>
					<option value="FV">Flat Value (FV)</option>
					<option value="MG">Missing Gap (MG)</option>
				</select>
			</div>
			<input type="hidden" name="data_type" value="RAIN">
			<input type="hidden" name="marked" value="{{ $status }}">
			<p></p>
			<div class="form-inline">
				<div class="form-group">
					<label for="">ตั้งแต่</label>
					<input name="start_date" type="date" class="form-control">
					<input name="start_time" type="time" class="form-control">
				</div>
				<div class="form-group">
					<label for="">ถึง</label>
					<input name="end_date" type="date" class="form-control">
					<input name="end_time" type="time" class="form-control">
				</div>
				<button type="submit" class="query_btn btn btn-primary">Go</button>
			</div>
		</form>
		<div id="div2" class="table-full monitor-table" style="width:100%"></div>			
	</div>
</div>

{{ HTML::style('css/watable.css'); }}
{{ HTML::script('js/jquery.watable.js'); }}
<script>
$(document).ready(function() {
	function HAIIWATable(divName, params){
		mainElement = $(divName);
	 	var waTable = mainElement.WATable({
			debug:true,                 //Prints some debug info to console
			pageSize: 20,                //Initial pagesize
			transition: 'fade',       //Type of transition when paging (bounce, fade, flip, rotate, scroll, slide).Requires https://github.com/daneden/animate.css.
			transitionDuration: 0.1,    //Duration of transition in seconds.
			filter: true,               //Show filter fields
			sorting: true,              //Enable sorting
			sortEmptyLast:true,         //Empty values will be shown last
			columnPicker: true,         //Show the columnPicker button
			pageSizes: [10,50,100,"All"],  //Set custom pageSizes. Leave empty array to hide button.
			hidePagerOnEmpty: true,     //Removes the pager if data is empty.
			checkboxes: false,           //Make rows checkable. (Note. You need a column with the 'unique' property)
			checkAllToggle:true,        //Show the check-all toggle
			preFill: true,              //Initially fills the table with empty rows (as many as the pagesize).
			url: '{{ URL::to('api/problems/all') }}',    //Url to a webservice if not setting data manually as we do in this example
			urlData: params,
			urlPost: false,             //Use POST httpmethod to webservice. Default is GET.
			types: {                    //Following are some specific properties related to the data types
				string: {
					//filterTooltip: "Giggedi..."    //What to say in tooltip when hoovering filter fields. Set false to remove.
					placeHolder: "Type to filter"    //What to say in placeholder filter fields. Set false for empty.
				},
				number: {
					decimals: 1   //Sets decimal precision for float types
				},
				bool: {
					//filterTooltip: false
				},
				date: {
				  utc: true,            //Show time as universal time, ie without timezones.
				  //format: 'yy/dd/MM',   //The format. See all possible formats here http://arshaw.com/xdate/#Formatting.
				  datePicker: true      //Requires "Datepicker for Bootstrap" plugin (http://www.eyecon.ro/bootstrap-datepicker).
				}
			},
			actions: false,
			// {                //This generates a button where you can add elements.
			//     filter: false,         //If true, the filter fields can be toggled visible and hidden.
			//     columnPicker: false,   //if true, the columnPicker can be toggled visible and hidden.
			//     custom: [             //Add any other elements here. Here is a refresh and export example.
			//       // $('<a href="#" class="refresh"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Refresh</a>'),
			//       // $('<a href="#" class="export_all"><span class="glyphicon glyphicon-share"></span>&nbsp;Export all rows</a>'),
			//       // $('<a href="#" class="export_checked"><span class="glyphicon glyphicon-share"></span>&nbsp;Export checked rows</a>'),
			//       // $('<a href="#" class="export_filtered"><span class="glyphicon glyphicon-share"></span>&nbsp;Export filtered rows</a>')
			//     ]
			// },
			tableCreated: function(data) {    //Fires when the table is created / recreated. Use it if you want to manipulate the table in any way.
				console.log('table created'); //data.table holds the html table element.
				console.log(data);            //'this' keyword also holds the html table element.
			},
			// rowClicked: function(data) {      //Fires when a row is clicked (Note. You need a column with the 'unique' property).
			// 	console.log('row clicked');   //data.event holds the original jQuery event.
			// 	console.log(data);            //data.row holds the underlying row you supplied.
			// 								  //data.column holds the underlying column you supplied.
			// 								  //data.checked is true if row is checked.
			// 								  //'this' keyword holds the clicked element.
			// },
			columnClicked: function(data) {    //Fires when a column is clicked
			  console.log('column clicked');  //data.event holds the original jQuery event
			  console.log(data);              //data.column holds the underlying column you supplied
											  //data.descending is true when sorted descending (duh)
			},
			pageChanged: function(data) {      //Fires when manually changing page
			  console.log('page changed');    //data.event holds the original jQuery event
			  console.log(data);              //data.page holds the new page index
			},
			pageSizeChanged: function(data) {  //Fires when manually changing pagesize
			  console.log('pagesize changed');//data.event holds teh original event
			  console.log(data);              //data.pageSize holds the new pagesize
			}
		}).data('WATable');  //This step reaches into the html data property to get the actual WATable object. Important if you want a reference to it as we want here.

		//Generate some data
		// var data = getData();
		// waTable.setData(data);  //Sets the data.
		//waTable.setData(data, true); //Sets the data but prevents any previously set columns from being overwritten
		//waTable.setData(data, false, false); //Sets the data and prevents any previously checked rows from being reset

		// var allRows = waTable.getData(false); //Gets the data you previously set.
		// var checkedRows = waTable.getData(true); //Gets the data you previously set, but with checked rows only.
		// var filteredRows = waTable.getData(false, true); //Gets the data you previously set, but with filtered rows only.

		// var pageSize = waTable.option("pageSize"); //Get option
		//waTable.option("pageSize", pageSize); //Set option

		//Example event handler triggered by the custom refresh link above.
		// $('body').on('click', '.refresh', function(e) {
		// 	e.preventDefault();
		// 	var data = getData();
		// 	waTable.setData(data, true);
		// });
		//Example event handler triggered by the custom export links above.
		// $('body').on('click', '.export_checked, .export_filtered, .export_all', function(e) {
		// 	e.preventDefault();
		// 	var elem = $(e.target);
		// 	var data;
		// 	if (elem.hasClass('export_all')) data = waTable.getData(false);
		// 	else if (elem.hasClass('export_checked')) data = waTable.getData(true);
		// 	else if (elem.hasClass('export_filtered')) data = waTable.getData(false, true);
		// 	console.log(data.rows.length + ' rows returned');
		// 	console.log(data);
		// 	alert(data.rows.length + ' rows returned.\nSee console for details.');
		// });
		function getFormObj(inputs) {
			var formObj = {};
			$.each(inputs, function (i, input) {
				formObj[input.name] = input.value;
			});
			return formObj;
		}
		mainElement.parent().find('form').on('click', '.query_btn', function(e){
			e.preventDefault();
			data = getFormObj($(this).parents('form').serializeArray());
			console.log(data);
			waTable.option("urlData", data);
			waTable.update();
		});

		mainElement.on('click', '.update', function(e){
			e.preventDefault();
			el = $(this);
			data = {
				id: el.data('id'),
				status: el.data('error')
			}
			// data.id = el.data('id');
			// data.status = el.data('error');
			$.post("{{ URL::to('api/problems/update_status') }}", data)
				.done(function(res){
					console.log(res);
					if(res.success){
						el.parent().parent().find('a[data-id="'+data.id+'"]').removeClass('active');
						el.addClass('active');
					}
				});
		});
	}
	new HAIIWATable("#div1", {
		data_type: 'WATER', 
		marked: '{{ $status }}'
	});
	new HAIIWATable("#div2", {
		data_type: 'RAIN', 
		marked: '{{ $status }}'
	});
});
</script>
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