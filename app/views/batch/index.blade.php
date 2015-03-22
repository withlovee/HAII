@extends('layouts.master', ['title' => 'Batch Processor'])

@section('content')
  <section class="panel panel-default" id="add-batch-task">
    <div class="panel-heading">
      <h3 class="panel-title">Add New Task</h3>
    </div>
    <div class="panel-body">
      {{ Form::open(array('url' => 'batch/create', 'method' => 'post', 'class' => 'form-inline')) }}
        <div class="panel panel-default task-data-problem-type">
          <div class="panel-heading">
            <input type="radio" name="dataType" class="task-data-problem-type-radio" id="" value="WATER"> Water Level
          </div>
          <div class="panel-body">
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="MG"> Missing Gap
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="FV"> Flat Value
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="OR"> Out of Range
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="OL"> Outliers
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="HM"> Inhomogeneity
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="waterProblemType[]" value="MP"> Missing Pattern
            </label>
          </div>
        </div>
        
        <div class="panel panel-default task-data-problem-type">
          <div class="panel-heading">
            <input type="radio" name="dataType" class="task-data-problem-type-radio" id="" value="RAIN"> Rain Level
          </div>
          <div class="panel-body">
            <label class="checkbox-inline">
              <input type="checkbox" name="rainProblemType[]" value="MG"> Missing Gap
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="rainProblemType[]" value="FV"> Flat Value
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="rainProblemType[]" value="OR"> Out of Range
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="rainProblemType[]" value="MP"> Missing Pattern
            </label>
          </div>
        </div>
        <h4>Stations</h4>
        <div class="form-class">
          <input type="checkbox" name="allStation" id="stations-all"> All Stations
        </div>
        <div class="form-class">
          <select multiple class="form-control chosen" id="stations-select" name="stations[]">
            @foreach ($stations as $station)
              <option value="{{ $station }}">{{ $station }}</option>
            @endforeach
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
      {{ Form::close() }}
    </div>
  </section>
  <section id="batch-task-log">
    <h2>All Tasks</h2>
    
    <table class="table table-bordered table-striped">
      <thead>
        <th>ID</th>
        <th>Data Type</th>
        <th>Problem Type</th>
        <th>Stations</th>
        <th>Start</th>
        <th>End</th>
        <th>Date Added</th>
        <th>Status</th>
        <th>Date Finished</th>
        <th>CSV</th>
      </thead>
      <tbody>
        @foreach($batches as $batch)
          <tr>
            <td>{{ $batch->id }}</td>
            <td>{{ $batch->data_type }}</td>
            <td>{{ join(', ',$batch->problem_type) }}</td>
            @if($batch->all_station)
            <td>ALL</td>
            @else
            <td>{{ join(', ', $batch->stations) }}</td>
            @endif
            <td>{{ $batch->start_datetime }}</td>
            <td>{{ $batch->end_datetime }}</td>
            <td>{{ $batch->add_datetime }}</td>
            <?php
            $statusClassMap = [
              'waiting' => 'label-default',
              'running' => 'label-info',
              'success' => 'label-success',
              'fail'    => 'label-danger'
            ];
            ?>
            <td>
              <span class="task-status label {{ $statusClassMap[$batch->status] }}">
                {{ ucwords($batch->status) }}
              </span>
            </td>
            <td>{{ $batch->finish_datetime }}</td>
            <td>
              @if($batch->status == 'success')
                <a class="btn btn-primary btn-xs" href="{{ asset('batchreport/'.$batch->id.'.csv') }}" target="_blank" download>Download</a>
              @elseif($batch->status == 'fail')
                <a class="btn btn-danger btn-xs" href="{{ asset('batchreport/'.$batch->id.'.log') }}" target="_blank" download>Log</a>
              @endif
            </td>
          </tr>
        @endforeach
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
      format: 'YYYY-MM-DD HH:mm:ss'
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