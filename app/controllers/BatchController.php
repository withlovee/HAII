<?php

use Symfony\Component\Process\Process;

class BatchController extends BaseController
{
    /**
     * Get batch task status and add task page
     * @return mixed
     */
    public function index()
    {
        $title = 'Batch Processor';
        $stations = TeleStation::codes();
        $batches = Batch::orderBy('id', 'desc')->get();

        return View::make('batch/index', compact('stations', 'title', 'batches'));
    }

    /**
     * create new batch task
     * @return mixed
     */
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
            $rules [] = 'waterProblemType';
        } elseif ($dataType == "RAIN") {
            $rules [] = 'rainProblemType';
        }
        if (!isset($data['allStation'])) {
            $rules [] = 'stations';
        }

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


        $batch = Batch::create($batchData);

        Queue::push('BatchController', ['id' => $batch->id]);

        return Redirect::to('batch')->with('alert-success', 'Task #'.$batch->id.' Added Successfully.');
    }

    /**
     * User cancel specific batch task
     * @param $id
     * @return mixed
     */
    public function cancel($id)
    {
        $batch = Batch::find($id);

        if ($batch->status != 'success' && $batch->status != 'fail') {
            $batch->cancel = true;
            $batch->save();

            return Redirect::to('batch')->with('alert-info', 'Task #'.$batch->id.' should be cancel in a moment.');
        } else {
            return Redirect::to('batch')->with('alert-danger', 'Task #'.$batch->id.' already finished!');
        }
    }

    /**
     * Message Queue Listener + Process task
     * @param job   $job            Laravel job object
     * @param object    $message    message detail (contains task id)
     */
    public function fire($job, $message)
    {
        $id = intval($message['id']);
        $batch = Batch::find($id);

        if ($batch->cancel) {
            $job->delete();
            $batch->setStatus('canceled');
            return;
        }

        $batch->setStatus('running');

        $batch->stampBeginExecDatetime();

        $rscriptCommand = Config::get('r.rscript');
        $rAppPath = base_path()."/rscripts/app.R batch_controller.R";
        $execCommand = $rscriptCommand." ".$rAppPath." ".$id;

        $process = new Process($execCommand);
        $process->start();

        while ($process->isRunning()) {
            usleep(200000);
            if (Batch::isCanceled($id)) {
                // Kill Process
                $process->stop(3, SIGINT);
                $batch->setStatus('canceled');
                $batch->stampFinishDatetime();
                $job->delete();

                return;
            }
        }

        $output = $process->getOutput();

        # get new status after exec R file
        $batch = Batch::find($id);

        $batch->stampFinishDatetime();

        # write to logfile
        $logfile = fopen(base_path().'/public/batchreport/'.$batch->id.".log", "w+");
        fwrite($logfile, $output);
        fclose($logfile);

        # Check if task if finish running
        if ($batch->status != "success") {
            $batch->setStatus('fail');
        }

        try {
            $job->delete();
        } catch (\Exception $e) {
        }
    }
}
