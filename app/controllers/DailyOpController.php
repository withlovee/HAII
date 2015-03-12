<?php

class DailyOpController extends ErrorLogController {

	public function index($status, $data_type) {
		$params = $this->getParams($status, $data_type);
		$data = $this->dataForForm($status, $data_type);
		// $params['start_date'] = date('Y-m-d', getTime());
		// $params['start_time'] = '07:01';
		$params['end_date_after'] = date('Y-m-d', getTime());
		$params['end_time_after'] = '07:01';
		$data['selectDate'] = false;
		$data['title'] = 'Daily Operations';
		$data['problems'] = Problem::allForTable($params);
		return View::make('dailyop/index', $data);
	}

}
