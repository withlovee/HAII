@extends('layouts.master', ['title' => 'Batch Processor'])

@section('content')
	<section class="panel panel-default" id="add-batch-task">
	  <div class="panel-heading">
	    <h3 class="panel-title">Add New Task</h3>
	  </div>
	  <div class="panel-body">
	    <form action="" class="form-inline">
	    	<div class="panel panel-default task-data-problem-type">
	    		<div class="panel-heading">
	    			<input type="radio" name="dataType" class="task-data-problem-type-radio" id="" value="WATER"> Water Level
	    		</div>
	    		<div class="panel-body">
	    			<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="MG"> Missing Gap
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="FV"> Flat Value
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="OR"> Out of Range
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="OL"> Outliers
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="HM"> Inhomogeneity
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="waterProblemType" value="MP"> Missing Pattern
						</label>
	    		</div>
	    	</div>
	    	
	    	<div class="panel panel-default task-data-problem-type">
	    		<div class="panel-heading">
	    			<input type="radio" name="dataType" class="task-data-problem-type-radio" id="" value="RAIN"> Rain Level
	    		</div>
	    		<div class="panel-body">
	    			<label class="checkbox-inline">
						  <input type="checkbox" name="rainProblemType" value="MG"> Missing Gap
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="rainProblemType" value="FV"> Flat Value
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="rainProblemType" value="OR"> Out of Range
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" name="rainProblemType" value="MP"> Missing Pattern
						</label>
	    		</div>
	    	</div>
	    	<h4>Stations</h4>
	    	<div class="form-class">
	    		<input type="checkbox" name="stations-all" value="true" id="stations-all"> All Stations
	    	</div>
	    	<div class="form-class">
	    		<select multiple class="form-control chosen" id="stations-select">
						@foreach ($stations as $station)
							<option value="{{ $station }}">{{ $station }}</option>
						@endforeach

					  <option value="ABRT  ">ABRT  </option><option value="ACRU  ">ACRU  </option><option value="ANLI  ">ANLI  </option><option value="ATG011">ATG011</option><option value="ATG021">ATG021</option><option value="ATG022">ATG022</option><option value="ATG031">ATG031</option><option value="ATG032">ATG032</option><option value="ATG041">ATG041</option><option value="ATG042">ATG042</option><option value="ATG051">ATG051</option><option value="ATG052">ATG052</option><option value="ATG061">ATG061</option><option value="ATG062">ATG062</option><option value="ATG071">ATG071</option><option value="ATG072">ATG072</option><option value="ATG081">ATG081</option><option value="ATG082">ATG082</option><option value="ATG091">ATG091</option><option value="ATG092">ATG092</option><option value="ATG101">ATG101</option><option value="ATG111">ATG111</option><option value="ATG112">ATG112</option><option value="ATG121">ATG121</option><option value="ATG122">ATG122</option><option value="ATG131">ATG131</option><option value="ATG132">ATG132</option><option value="ATG141">ATG141</option><option value="ATG142">ATG142</option><option value="ATG151">ATG151</option><option value="ATG152">ATG152</option><option value="ATG161">ATG161</option><option value="ATG162">ATG162</option><option value="ATG171">ATG171</option><option value="ATG172">ATG172</option><option value="ATG181">ATG181</option><option value="ATG182">ATG182</option><option value="BAKI  ">BAKI  </option><option value="BARI  ">BARI  </option><option value="BBHN  ">BBHN  </option><option value="BBON  ">BBON  </option><option value="BBUA  ">BBUA  </option><option value="BBWN  ">BBWN  </option><option value="BCAP  ">BCAP  </option><option value="BCNG  ">BCNG  </option><option value="BDAR  ">BDAR  </option><option value="BDCP  ">BDCP  </option><option value="BDGN  ">BDGN  </option><option value="BDLH  ">BDLH  </option><option value="BDLM  ">BDLM  </option><option value="BDMG  ">BDMG  </option><option value="BDWN  ">BDWN  </option><option value="BGBO  ">BGBO  </option><option value="BGRT  ">BGRT  </option><option value="BGSI  ">BGSI  </option><option value="BHMN  ">BHMN  </option><option value="BHPK  ">BHPK  </option><option value="BHRA  ">BHRA  </option><option value="BHUN  ">BHUN  </option><option value="BHYD  ">BHYD  </option><option value="BJAN  ">BJAN  </option><option value="BJIG  ">BJIG  </option><option value="BKDN  ">BKDN  </option><option value="BKHI  ">BKHI  </option><option value="BKHL  ">BKHL  </option><option value="BKHN  ">BKHN  </option><option value="BKJO  ">BKJO  </option><option value="BKK001">BKK001</option><option value="BKK002">BKK002</option><option value="BKK003">BKK003</option><option value="BKK004">BKK004</option><option value="BKK005">BKK005</option><option value="BKK006">BKK006</option><option value="BKK007">BKK007</option><option value="BKK008">BKK008</option><option value="BKK009">BKK009</option><option value="BKK010">BKK010</option><option value="BKK011">BKK011</option><option value="BKK012">BKK012</option><option value="BKLK  ">BKLK  </option><option value="BKNH  ">BKNH  </option><option value="BKUG  ">BKUG  </option><option value="BKWN  ">BKWN  </option><option value="BLAT  ">BLAT  </option><option value="BLD1  ">BLD1  </option><option value="BLKO  ">BLKO  </option><option value="BLNG  ">BLNG  </option><option value="BLUG  ">BLUG  </option><option value="BMDG  ">BMDG  </option><option value="BMKN  ">BMKN  </option><option value="BMKO  ">BMKO  </option><option value="BMNK  ">BMNK  </option><option value="BMOD  ">BMOD  </option><option value="BMUA  ">BMUA  </option><option value="BNAK  ">BNAK  </option><option value="BNAN  ">BNAN  </option><option value="BNGR  ">BNGR  </option><option value="BNHG  ">BNHG  </option><option value="BNHI  ">BNHI  </option><option value="BNHO  ">BNHO  </option><option value="BNKE  ">BNKE  </option><option value="BNKK  ">BNKK  </option><option value="BNKN  ">BNKN  </option><option value="BNKP  ">BNKP  </option><option value="BNLG  ">BNLG  </option><option value="BNMK  ">BNMK  </option><option value="BNPI  ">BNPI  </option><option value="BNPK  ">BNPK  </option>
					</select>
	    	</div>
	    	<h4>Range</h4>
	    	<div class="form-group">
	    		From <input type="text" name="startDateTime" class="task-time datetimepicker" id="task-start-time">
	    		 to 
	    		<input type="text" name="endDateTime" class="task-time datetimepicker" id="task-end-time">
	    	</div>
	    	<div class="form-group">
		    	<button type="submit" class="btn btn-primary">Submit Task</button>
	    	</div>
	    </form>
    </div>
	</section>
	<section id="batch-task-log">
		<h2>All Tasks</h2>
		<table class="table table-bordered table-striped">
			<thead>
				<th>Data Type</th>
				<th>Problem Type</th>
				<th>Start</th>
				<th>End</th>
				<th>Date Added</th>
				<th>Status</th>
				<th>Date Finished</th>
				<th>CSV</th>
			</thead>
			<tbody>
				<tr>
					<td>WATER</td>
					<td>MG, OL, HM</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td><span class="task-status label label-default">Waiting</span></td>
					<td>{{ strftime("%c") }}</td>
					<td><a class="btn btn-primary btn-xs" href="#">Download</a></td>
				</tr>
				<tr>
					<td>WATER</td>
					<td>MG, OL, HM</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td><span class="task-status label label-info">Running</span></td>
					<td>{{ strftime("%c") }}</td>
					<td><a class="btn btn-primary btn-xs" href="#">Download</a></td>
				</tr>
				<tr>
					<td>WATER</td>
					<td>MG, OL, HM</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td><span class="task-status label label-success">Success</span></td>
					<td>{{ strftime("%c") }}</td>
					<td><a class="btn btn-primary btn-xs" href="#">Download</a></td>
				</tr>
				<tr>
					<td>WATER</td>
					<td>MG, OL, HM</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td>{{ strftime("%c") }}</td>
					<td><span class="task-status label label-danger">Fail</span></td>
					<td>{{ strftime("%c") }}</td>
					<td><a class="btn btn-primary btn-xs" href="#">Download</a></td>
				</tr>
			</tbody>
		</table>
	</section>
@stop

@section('script')

	{{ HTML::script('js/moment.js') }}
	{{ HTML::script('js/bootstrap-datetimepicker.min.js'); }}

	<script>
	$(function() {

		// Water/Rain radio interaction
		$('.task-data-problem-type > .panel-body').hide();

		$('.task-data-problem-type-radio').change(function(){
			$('.task-data-problem-type-radio').parents('.task-data-problem-type').removeClass('panel-primary').children('.panel-body').slideUp();
			$(this).parents('.task-data-problem-type').addClass('panel-primary').children('.panel-body').slideDown();
		});

		// Date/Time picker
		$('.datetimepicker').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });

    $('#stations-all').change(function(){
    	if ($(this).is(':checked')) {
    		$('#stations-select').prop('disabled', true);
    	} else {
    		$('#stations-select').prop('disabled', false);
    	}
    	$('#stations-select').trigger('chosen:updated');
    });

  });
	</script>
@stop