<?php

class ErrorLogController extends BaseController {

	public function index($status) {
		$data = $this->dataForForm();
		$data[$status] = 'active';
		$data['selectDate'] = true;
		$data['title'] = 'Error Log';
		if($status == 'marked') $data['status'] = 'true';
		elseif($status == 'all') $data['status'] = 'all';
		else $data['status'] = 'false';
		return View::make('errorlog/index', $data);
	}

	protected function dataForForm() {
		$data = array();
		$data['basins'] = TeleStation::basins(true);
		$data['parts'] = TeleStation::parts(true);
		$data['provinces'] = TeleStation::provinces(true);
		$data['codes'] = TeleStation::codes(true);
		$data['marked'] = '';
		$data['unmarked'] = '';
		$data['all'] = '';
		return $data;
	}
}
