<?php

/**
 * Class ErrorLogController
 * Page for viewing all problems found
 */
class ErrorLogController extends BaseController
{
    protected $params_defaults = array(
        'data_type' => '',
        'basin' => '',
        'province' => '',
        'part' => '',
        'code' => '',
        'problem_type' => '',
        'marked' => '',
        'start_date' => '',
        'start_time' => '',
        'end_date' => '',
        'end_time' => '',
        'orderby' => 'start_datetime',
    );

    /**
     * View all problems
     * @param string    $status     status of problem ("all", "marked", "unmarked")
     * @param string    $data_type  WATER / RAIN
     * @return mixed
     */
    public function index($status, $data_type)
    {
        $params = $this->getParams($status, $data_type);
        $data = $this->dataForForm($status, $data_type);
        $data['selectDate'] = true;
        $data['title'] = 'Error Log';

        Log::info($params);

        $data['problems'] = Problem::allForTable($params);

        return View::make('errorlog/index', $data);
    }

    /**
     * filtered page by mark / unmark
     * @param string    $status     status of problem ("all", "marked", "unmarked")
     * @return string
     */
    protected function getMarked($status)
    {
        if ($status == 'marked') {
            return 'true';
        } elseif ($status == 'unmarked') {
            return 'false';
        } else {
            return '';
        }
    }

    /**
     * Retrieve all POST input
     * @param $status
     * @param $data_type
     * @return mixed
     */
    protected function getParams($status, $data_type)
    {
        $params = Input::all();
        $params['marked'] = $this->getMarked($status);
        $params['data_type'] = strtoupper($data_type);

        return $params;
    }

    /**
     * Initial data for error_log filter
     * string    $status     status of problem ("all", "marked", "unmarked")
     * @param $data_type
     * @return array
     */
    protected function dataForForm($status, $data_type)
    {
        $params = Input::all();
        $data = $this->getSelectedValues($params);
        $data['basins'] = TeleStation::basins(true);
        $data['parts'] = TeleStation::parts(true);
        $data['provinces'] = TeleStation::provinces(true);
        $data['codes'] = TeleStation::codes(true);
        $data['marked'] = '';
        $data['unmarked'] = '';
        $data['start_date'] = '';
        $data['start_time'] = '';
        $data['all'] = '';
        $data['water'] = '';
        $data['rain'] = '';

        $data[$status] = 'active';
        $data[$data_type] = 'active';
        $data['data_type'] = $data_type;
        $data['url_status'] = $status;
        if ($status == 'marked') {
            $data['status'] = 'true';
        } elseif ($status == 'all') {
            $data['status'] = 'all';
        } else {
            $data['status'] = 'false';
        }

        return $data;
    }

    /** merge data from html form of water/rain to one parameter array
     * @param array     $params     POST input from view
     * @return array
     */
    protected function getSelectedValues($params)
    {
        $data = array();
        $data['params_rain'] = $this->params_defaults;
        $data['params_water'] = $this->params_defaults;
        $data['params'] = array();
        if (array_key_exists('data_type', $params) && $params['data_type'] == 'WATER') {
            $data['params_water'] = array_merge($data['params_water'], $params);
            $data['params'] = &$data['params_water'];
        } elseif (array_key_exists('data_type', $params) && $params['data_type'] == 'RAIN') {
            $data['params_rain'] = array_merge($data['params_rain'], $params);
            $data['params'] = &$data['params_rain'];
        }

        return $data;
    }
}
