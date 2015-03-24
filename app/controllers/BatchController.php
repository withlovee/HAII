<?php

class BatchController extends BaseController
{
    public function index()
    {
        $title = 'Batch Processor';
        $stations = TeleStation::codes();
        $batches = Batch::orderBy('id', 'desc')->get();

        return View::make('batch/index', compact('stations', 'title', 'batches'));
    }

    public function create()
    {
        $data = Input::all();
        $batchData = [];

        $dataTypeValidator = Validator::make(Input::all(), ['dataType' => 'required']);
        if ($dataTypeValidator->fails()) {
            return Redirect::back()->withInput()->with('alert-danger', 'Data type is missing!');
        }

        $dataType = $data['dataType'];
        $rules = ['startDateTime' , 'endDateTime'];
        if ($dataType == "WATER") {
            $rules []= 'waterProblemType';
        } elseif ($dataType == "RAIN") {
            $rules []= 'rainProblemType';
        }
        if (!isset($data['allStation'])) {
            $rules []= 'stations';
        }

        // $validator = Validator::make(Input::all(), $rules);
        foreach ($rules as $r) {
            if (!isset($data[$r])) {
                return Redirect::back()->withInput()->with('alert-danger', $r.' is incomplete!');
            }
        }

        if ($data['startDateTime'] == "" or $data['endDateTime'] == "") {
            return Redirect::back()->withInput()->with('alert-danger', 'Date is incomplete!');
        }


        $batchData['data_type'] = $data['dataType'];

        if ($batchData['data_type'] == "WATER") {
            $batchData['problem_type'] = $data['waterProblemType'];
        } elseif ($batchData['data_type'] == "RAIN") {
            $batchData['problem_type'] = $data['rainProblemType'];
        }

        if (isset($data['allStation'])) {
            $batchData['all_station'] = true;
        } else {
            $batchData['all_station'] = false;
            $batchData['stations'] = $data['stations'];
        }

        $batchData['start_datetime'] = Date($data['startDateTime']);
        $batchData['end_datetime'] = Date($data['endDateTime']);
        $batchData['add_datetime'] = Date('Y-m-d H:i:s');

        // $batchData['problem_type'] = serialize($batchData['problem_type']);
        // $batchData['stations'] = serialize($batchData['stations']);
        // dd($batchData);
        $batch = Batch::create($batchData);

        Queue::push('BatchController', ['id' => $batch->id]);

        return Redirect::to('batch')->with('alert-success', 'Task #'.$batch->id.' Added Successfully.');

        
    }

    public function fire($job, $data)
    {
        # for testing
        // $data['id'] = 5;
        $id = intval($data['id']);
        $batch = Batch::find($id);
        $batch->status = "running";
        $batch->save();

        Log::info("Batch: Task #".$id);
        # Execute R Task
        $rscriptCommand = Config::get('r.rscript');
        $rAppPath = base_path()."/rscripts/app.R batch_controller.R";
        $execCommand = $rscriptCommand." ".$rAppPath." ".$id;

        $result = shell_exec($execCommand);

        # get new status after exec R file
        $batch = Batch::find($id);

        # write to logfile
        $logfile = fopen(base_path().'/public/batchreport/'.$batch->id.".log", "w+");
        fwrite($logfile, $result);
        fclose($logfile);

        # Check if task if finish running
        if ($batch->status != "success") {
            $batch->status = "fail";
            $batch->save();
        }

        $job->delete();
        Log::info("Batch: Finish Task #".$id);
    }
}
