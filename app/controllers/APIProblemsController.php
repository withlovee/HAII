<?php

class APIProblemsController extends BaseController
{
    /**
     * Get all problem to show in error_log page
     * @return mixed
     */
    public function all()
    {
        $params = Input::all();
        $output['cols'] = $this->getCols();
        $output['rows'] = $this->getRows(Problem::allForTable($params));

        return Response::json($output);
    }

    /**
     * Update problem flag
     * @return mixed
     */
    public function updateStatus()
    {
        $problem = Problem::find(intval(Input::get('id')));

        $problem->status = Input::get('status');
        $res = $problem->save();

        Log::info(print_r($res, true));

        if ($res) {
            Log::info("res true");

            if ($problem->status == "true") {
                DataLog::setValToNull($problem);
                // $this->setErrorToNull($problem);
            } elseif ($problem->status == "false" || $problem->status == "undefined") {
                DataLog::restoreVal($problem);
                // $this->setErrorToOrigin($problem);
            }

            return Response::json(['success' => $res]);
        } else {
            Log::info("res false");

            return Response::make([], 400);
        }
    }


    /**
     * Get detailed station info
     * @return mixed
     */
    public function renderStationInfo()
    {
        $station = Input::get('station');

        return View::make('data_log/station_info', $station);
    }

    /**
     * Get recent problem and render to map dashboard
     * @return mixed
     */
    public function getMap()
    {
        $output = Problem::recentMap();

        return Response::json($output);
    }

    /**
     * Get problem from specific problem id
     * @return mixed
     */
    public function getProblem()
    {
        $problem = Problem::find(intval(Input::get('id')));


        $data_log = null;

        $data_log_query = DataLog::code($problem->station->code)->valid($problem->data_type);

        if ($problem->data_type == "WATER") {
            $data_log_query = $data_log_query->from($problem->start_datetime, 7200)
                                                                             ->to($problem->end_datetime, 7200)
                                                                             ->cleanOrigin("WATER");
        } elseif ($problem->data_type == "RAIN") {
            $data_log_query = $data_log_query->from($problem->start_datetime, 3600*5)
                                                                             ->to($problem->end_datetime, 3600*5)
                                                                             ->hourly()
                                                                             ->cleanOrigin("RAIN");
        }

        $data_log = $data_log_query->get()->toArray();

        $data_log_new = array();
        foreach ($data_log as $item) {
            if ($problem->data_type == 'WATER') {
                $data_log_new[] = [
                    strtotime($item['date'].' '.$item['time']) * 1000,
                    // floatval($item['water1'])
                    floatval($item['origin_water1']),
                ];
            } else {
                $data_log_new[] = [
                    strtotime($item['date'].' '.$item['time']) * 1000,
                    // floatval($item['rain1h']),
                    floatval($item['origin_rain1h']),
                ];
            }
        }
        $output = array(
            'id' => $problem->id,
            'data_type' => $problem->data_type,
            'problem_type' => $problem->problem_type,
            'start_datetime' => $problem->start_datetime,
            'start_datetime_unix' => strtotime($problem->start_datetime),
            'end_datetime' => $problem->end_datetime,
            'end_datetime_unix' => strtotime($problem->end_datetime),
            'num' => $problem->num,
            'station' => array(
                'name' => $problem->station->name,
                'code' => $problem->station->code,
                'tambon_name' => $problem->station->tambon_name,
                'amphoe_name' => $problem->station->amphoe_name,
                'province_name' => $problem->station->province_name,
                'part' => $problem->station->part,
                'basin' => $problem->station->basin,
            ),
            'data' => $data_log_new,
        );

        return Response::json($output);
    }

    /**
     * Get graph result of missing pattern (plotted from R in public/missingpattern)
     * @return mixed
     */
    public function getMissingPatternMonthlyImage()
    {
        $id = intval(Input::get('id'));
        $problem = Problem::find($id);
        // dd($problem);
        $datetime = strtotime($problem->start_datetime);

        $filename = date('Y-m-01', $datetime).'_'.$problem->station_code.'_'.$problem->data_type.'.png';

        return Response::json(asset('missingpattern/'.$filename));
    }

    /**
     * Return HTML buttons for flagging problem status
     * @return string
     */
    public function getButtons()
    {
        if (!isAdmin()) {
            return "Log in ด้วยสิทธิ Admin เพื่อเปลี่ยนสถานะของปัญหา";
        }

        $problem = Problem::find(intval(Input::get('id')));
        $type = $problem->problem_type;
        $status = $problem->status;
        $html = '';
        // 'Error' Button
        if ($type != 'HM' && $type != 'MP') {
            $html .= $this->getErrorButton($problem->id, 'true', $status, 'btn btn-default');
        }
        //$html .= ' &nbsp; ';
        // 'Not Error' Button
        $html .= $this->getErrorButton($problem->id, 'false', $status, 'btn btn-default');

        $html .= $this->getErrorButton($problem->id, 'undefined', $status, 'btn btn-default');

        return $html;
        // return Response::json($output);
    }

    /**
     * Get array of column in error_log page
     * @return array
     */
    private function getCols()
    {
        return array(
            'start_datetime' => array(
                'index' => 1,
                'type' => 'date',
                // 'format' => 'yyyy/MM/dd HH:mm:ss',
                'friendly' => 'วันเวลาที่เริ่ม',
            ),
            'code' => array(
                'index' => 2,
                'type' => 'string',
                'friendly' => 'รหัสสถานี',
            ),
            'station_name' => array(
                'index' => 3,
                'type' => 'string',
                'friendly' => 'ชื่อสถานี',
            ),
            'problem_type' => array(
                'index' => 3,
                'type' => 'string',
                'friendly' => 'ประเภทของปัญหา',
            ),
            'num' => array(
                'index' => 4,
                'type' => 'number',
                'friendly' => 'จำนวน',
            ),
            'is_error' => array(
                'index' => 5,
                'type' => 'string',
                'filter' => false,
                'friendly' => 'ใช่ปัญหา',
            ),
            'is_not_error' => array(
                'index' => 6,
                'type' => 'string',
                'filter' => false,
                'friendly' => 'ไม่ใช่ปัญหา',
            ),
        );
    }

    /**
     * Get each row in data_log page
     * @param $problems
     * @return mixed
     */
    private function getRows($problems)
    {
        foreach ($problems as $problem) {
            $problem['station_name'] = '<a href="" class="model_btn" data-id="'.$problem['id'].'" data-toggle="modal" data-target="#detail">'.$problem['station_name'].'</a>';
            $problem['is_error'] = $this->getErrorButton($problem['id'], 'true', $problem['status'] == 'true');
            $problem['is_not_error'] = $this->getErrorButton($problem['id'], 'false', $problem['status'] == 'false');
        }

        return $problems;
    }

    /**
     * Get error flag button in error log and modal
     * @param $id               problem id
     * @param $error            type of button
     * @param bool $default     active flag of this problem
     * @param $classes          addtional html classes
     * @return string
     */
    private function getErrorButton($id, $error, $default = false, $classes)
    {
        $class = ' '.$classes;
        if ($error == $default) {
            $class .= ' active';
        }

        if ($error == "true") {
            return '<a href="#" data-error="true" data-id="'.$id.'" class="update error'.$class.'"><span class="glyphicon glyphicon-exclamation-sign"></span><!--<span class="text">Error</span></a>-->';
        } elseif ($error == "false") {
            return '<a href="#" data-error="false" data-id="'.$id.'" class="update noterror'.$class.'"><span class="glyphicon glyphicon-ok-sign"></span><!--<span class="text">Not Error</span></a>-->';
        } else {
            return '<a href="#" data-error="undefined" data-id="'.$id.'" class="update undefined'.$class.'"><span class="glyphicon glyphicon-question-sign"></span><!--<span class="text">Undefined</span></a>-->';
        }
    }
}
