<?php

class ErrorLogController extends BaseController {

	public function index($status) {
		$data = array();
		$data['basins'] = TeleStation::basins(true);
		$data['parts'] = TeleStation::parts(true);
		$data['provinces'] = TeleStation::provinces(true);
		$data['codes'] = TeleStation::codes(true);
		$data['marked'] = '';
		$data['unmarked'] = '';
		$data[$status] = 'active';
		if($status == 'marked') $data['status'] = 'true';
		else $data['status'] = 'false';
		return View::make('errorlog/index', $data);
	}
}