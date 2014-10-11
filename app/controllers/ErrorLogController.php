<?php

class ErrorLogController extends BaseController {

	public function index() {
		$data = array();
		$data['basins'] = TeleStation::basins(true);
		$data['provinces'] = TeleStation::provinces(true);
		$data['codes'] = TeleStation::codes(true);
		return View::make('errorlog/index', $data);
	}

	public function get() {
		$params = Input::all();
		$output['cols'] = array(
			'id' => array(
				'index' => 1,
				'type' => 'number',
				'sortOrder' => 'desc',
				'unique' => true,
				'friendly' => 'ID'
			),
			'station_name' => array(
				'index' => 2,
				'type' => 'string',
				'friendly' => 'ชื่อสถานี'
			),
			'start_datetime' => array(
				'index' => 3,
				'type' => 'date',
				// 'format' => 'yyyy/MM/dd HH:mm:ss',
				'friendly' => 'วันเวลาที่เริ่ม'
			),
			'problem_type' => array(
				'index' => 3,
				'type' => 'string',
				'friendly' => 'ประเภทของปัญหา'
			),
			'num' => array(
				'index' => 4,
				'type' => 'number',
				'friendly' => 'จำนวนปัญหา'
			),
			'is_error' => array(
				'index' => 5,
				'type' => 'string',
				'filter' => false,
				'friendly' => 'ใช่ปัญหา'
			),
			'is_not_error' => array(
				'index' => 6,
				'type' => 'string',
				'filter' => false,
				'friendly' => 'ไม่ใช่ปัญหา'
			),
		);
		$output['rows'] = $this->getRows(Problem::allForTable($params));
		return $output;
	}

	private function getRows($problems) {
		foreach($problems as $problem){
			$problem['station_name'] = '<a href="" data-toggle="modal" data-target="#detail">'.$problem['station_name'].'</a>';
			$problem['is_error'] = $this->getErrorButton($problem['id'], true);
			$problem['is_not_error'] = $this->getErrorButton($problem['id'], false);
		}
		return $problems;
	}

	private function getErrorButton($id, $is_error, $default = false) {
		if($default)
			$class = ' active';
		else
			$class = '';
		if($is_error)
			return '<a href="#" data-error="true" data-id="'.$id.'" class="update'.$class.'"><span class="glyphicon glyphicon-ok"></span> <span class="text">Error</span></a>';
		else
			return '<a href="#" data-error="false" data-id="'.$id.'" class="update'.$class.'""><span class="glyphicon glyphicon-remove"></span> <span class="text">Not Error</span></a>';
	}
}
