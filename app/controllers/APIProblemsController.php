<?php

class APIProblemsController extends BaseController {


	public function all() {
		$params = Input::all();
		$output['cols'] = $this->getCols();
		$output['rows'] = $this->getRows(Problem::allForTable($params));
		return Response::json($output);
	}
	
	public function updateStatus() {
		$problem = Problem::find(intval(Input::get('id')));
		$problem->status = Input::get('status');
		$res = $problem->save();
		if($res)
			return Response::json(['success' => $res]);
		else
			return Response::make([], 400);
	}

	public function renderStationInfo() {
		$station = Input::get('station');
		return View::make('data_log/station_info', $station);
	}

	public function getMap() {
		$output = Problem::recentMap();
		return Response::json($output);
	}

	public function getProblem() {
		$problem = Problem::find(intval(Input::get('id')));
		$data_log = DataLog::code($problem->station->code)
			->from($problem->start_datetime)
			->to($problem->end_datetime)
			->valid($problem->data_type)
			->take(1000)
			->get()
			->toArray();
		$data_log_new = array();
		foreach($data_log as $item){
			if($problem->data_type == 'WATER')
				$data_log_new[] = [
					strtotime($item['date'].' '.$item['time']) * 1000,
					floatval($item['water1'])
				];
			else
				$data_log_new[] = [
					strtotime($item['date'].' '.$item['time']) * 1000,
					floatval($item['rain10m']),
				];
		}
		$output = array(
			'id' => $problem->id,
			'data_type' => $problem->data_type,
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
			'data' => $data_log_new
		);
		return Response::json($output);
	}

	private function getCols() {
		return array(
			// 'id' => array(
			// 	'index' => 1,
			// 	'type' => 'number',
			// 	'sortOrder' => 'desc',
			// 	'unique' => true,
			// 	'friendly' => 'ID'
			// ),
			'start_datetime' => array(
				'index' => 1,
				'type' => 'date',
				// 'format' => 'yyyy/MM/dd HH:mm:ss',
				'friendly' => 'วันเวลาที่เริ่ม'
			),
			'code' => array(
				'index' => 2,
				'type' => 'string',
				'friendly' => 'รหัสสถานี'
			),
			'station_name' => array(
				'index' => 3,
				'type' => 'string',
				'friendly' => 'ชื่อสถานี'
			),
			'problem_type' => array(
				'index' => 3,
				'type' => 'string',
				'friendly' => 'ประเภทของปัญหา'
			),
			'num' => array(
				'index' => 4,
				'type' => 'number',
				'friendly' => 'จำนวน'
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
	}

	private function getRows($problems) {
		foreach($problems as $problem){
			$problem['station_name'] = '<a href="" class="model_btn" data-id="'.$problem['id'].'" data-toggle="modal" data-target="#detail">'.$problem['station_name'].'</a>';
			$problem['is_error'] = $this->getErrorButton($problem['id'], true, $problem['status'] == 'true');
			$problem['is_not_error'] = $this->getErrorButton($problem['id'], false, $problem['status'] == 'false');
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
			return '<a href="#" data-error="false" data-id="'.$id.'" class="update'.$class.'"><span class="glyphicon glyphicon-remove"></span> <span class="text">Not Error</span></a>';
	}

}
