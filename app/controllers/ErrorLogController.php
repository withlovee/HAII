<?php

class ErrorLogController extends BaseController {

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
	);

	public function index($status, $data_type) {
		$params = $this->getParams($status, $data_type);
		$data = $this->dataForForm($status, $data_type);
		$data['selectDate'] = true;
		$data['title'] = 'Error Log';
		$data['problems'] = Problem::allForTable($params);
		return View::make('errorlog/index', $data);
	}

	protected function getMarked($status){
		if($status == 'marked'){
			return 'true';
		}
		elseif($status == 'unmarked'){
			return 'false';
		}
		else
			return '';
	}

	protected function getParams($status, $data_type){
		$params = Input::all();
		$params['marked'] = $this->getMarked($status);
		$params['data_type'] = strtoupper($data_type);
		return $params;
	}

	protected function dataForForm($status, $data_type){
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
		if($status == 'marked') $data['status'] = 'true';
		elseif($status == 'all') $data['status'] = 'all';
		else $data['status'] = 'false';
		return $data;
	}

	protected function getSelectedValues($params) {
		$data = array();
		$data['params_rain'] = $this->params_defaults;
		$data['params_water'] = $this->params_defaults;
		$data['params'] = array();
		if(array_key_exists('data_type', $params) && $params['data_type'] == 'WATER'){
			$data['params_water'] = array_merge($data['params_water'], $params);
			$data['params'] = &$data['params_water'];
		}
		elseif(array_key_exists('data_type', $params) && $params['data_type'] == 'RAIN'){
			$data['params_rain'] = array_merge($data['params_rain'], $params);
			$data['params'] = &$data['params_rain'];
		}
		return $data;
	}

}
