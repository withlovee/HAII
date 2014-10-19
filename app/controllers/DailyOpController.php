<?php

class DailyOpController extends ErrorLogController {

	public function index($status) {
		
		$data = parent::dataForForm();
		$data['start_date'] = date('Y-m-d', getTime());
		$data['start_time'] = '07:01';
		$data[$status] = 'active';
		$data['selectDate'] = false;
		$data['title'] = 'Daily Operations<br><small>แสดงปัญหาที่เกิดขึ้นตั้งแต่วันที่ '.thai_date(getTime()).date('Y', getTime()).' เวลา 7.01 น.</small>';

		if($status == 'marked') $data['status'] = 'true';
		elseif($status == 'all') $data['status'] = 'all';
		else $data['status'] = 'false';

		return View::make('dailyop/index', $data);
	}

}
