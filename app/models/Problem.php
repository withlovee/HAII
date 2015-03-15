<?php

class Problem extends \Eloquent {
	protected $fillable = [];

	public function scopeDataType($query, $type){
		$query->join('tele_station', 'problems.station_code', '=', 'tele_station.code')
			->select('problems.id', 'code', 
				'name', 
				'problem_type',
				'tambon_name', 
				'amphoe_name', 
				'province_name', 
				'part',
				'basin', 
				'start_datetime', 
				'end_datetime', 
				'num',
				'problems.status'
				);
		if($type) return $query->where('data_type', '=', $type);
		return $query;
	}

	public function scopeMap($query){
		$attrs = ['problem_type', 'code', 'name', 'lat', 'lng', 'tambon_name', 'amphoe_name', 'province_name', 'part', 'basin'];
		$query->join('tele_station', 'problems.station_code', '=', 'tele_station.code')
			->selectRaw(implode(", ",$attrs).', sum(num) as num')
			->groupBy($attrs);
		return $query;
	}
	public function scopeBasin($query, $basin){
		if($basin) return $query->where('basin', '=', $basin);
		return $query;
	}
	public function scopeProvince($query, $province){
		if($province) return $query->where('province_name', '=', $province);
		return $query;
	}
	public function scopePart($query, $part){
		if($part) return $query->where('part', '=', $part);
		return $query;
	}
	public function scopeCode($query, $code){
		if($code) return $query->where('code', '=', $code);
		return $query;
	}
	public function scopeProblem($query, $problem_type){
		if($problem_type) return $query->where('problem_type', '=', $problem_type);
		return $query;
	}
	public function scopeMarked($query, $marked){
		if($marked == 'true') return $query->where('problems.status', '!=', 'undefined');
		elseif($marked == 'false') return $query->where('problems.status', '=', 'undefined');
		return $query;
	}
	public function scopeStartDatetime($query, $datetime){
		if($datetime) return $query->where('start_datetime', '>=', $datetime);
		else return $query;
	}
	public function scopeEndDatetime($query, $datetime){
		if($datetime) return $query->where('start_datetime', '<=', $datetime);
		else return $query;
	}

	public function scopeEndDatetimeAfter($query, $datetime) {
		if($datetime) return $query->where('end_datetime', '>=', $datetime);
		else return $query;	
	}

	static function recentByBasin($data_type, $problem_type = 'OR') {
		/*-- Query latest problems with tele_station information --*/
		$problems = self::dataType($data_type)
			->problem($problem_type)
			->marked('false')
			->endDatetimeAfter(self::getStartDate('Y-m-d 07:01'))
			->get()->toArray();
		// return self::getStartDate('Y-m-d 07:01');
		/*-- Group the data by basins --*/
		$grouped_problems = array();
		foreach($problems as $problem){
			$problem['full_name'] = self::buildFullStationName($problem);
			$problem['end_time'] = date('H:i', strtotime($problem['end_datetime']));
			if($problem['basin']){
				$grouped_problems[$problem['basin']][] = $problem;
			}
			// If the station has no basin information
			else{
				$grouped_problems['none'][] = $problem;
			}
		}
		return $grouped_problems;
	}

	static function recentMap(){
		/*-- Query telestations that has recent problems --*/
		$problems = self::map()->endDatetimeAfter(self::getStartDate('Y-m-d 07:01'))->get()->toArray();
		foreach($problems as $i => $problem){
			$problems[$i]['full_name'] = self::buildFullStationName($problem);
			unset($problems[$i]['tambon_name']);
			unset($problems[$i]['amphoe_name']);
			unset($problems[$i]['province_name']);
			unset($problems[$i]['name']);

			// $problems[$i]['problem_type'] = array_values(array_unique(explode(',' , substr($problem['problem_type'], 1, -1))));
		}
		return $problems;
	}

	static function allForTable($params){
		$defaults = array(
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
			'end_date_after' => '',
			'end_time_after' => '',
			'end_time' => '',
			'orderby' => 'start_datetime'
		);
		$params = array_merge($defaults, $params);

		$orderby_order = array(
				'start_datetime' => 'desc',
				'station_code' => 'asc'
			);

		$order = $params['orderby'];

		$problems = self::dataType($params['data_type'])
			->basin($params['basin'])
			->province($params['province'])
			->part($params['part'])
			->code($params['code'])
			->problem($params['problem_type'])
			->marked($params['marked'])
			->startDatetime(self::renderDate($params['start_date'], $params['start_time']))
			->endDatetime(self::renderDate($params['end_date'], $params['end_time']))
			->endDatetimeAfter(self::renderDate($params['end_date_after'], $params['end_time_after']))
			// ->endDatetimeAfter(self::renderDate($params['end_date_after'], $params['end_time_after'])
			->orderBy($order, $orderby_order[$order])
			->orderBy('id', 'desc')
			->paginate(25);
			// ->get();
		// foreach($problems as $problem){
		// 	$problem['station_name'] = self::buildFullStationName($problem);
		// 	unset($problem['station']);
		// 	$problem['problem_type'] = self::getTypeName($problem['problem_type']);
		// }
		return $problems;
	}

	static private function renderDate($date, $time){
		if(!$date) return null;
		if(!$time) $time = '00:00';
		return $date.' '.str_replace("%3A", ":", $time);
	}

	static function getTypeName($name){

		$nameMap = array(
				'OR' => 'Out of Range',
				'FV' => 'Flat Value',
				'MG' => 'Missing Gap',
				'OL' => 'Outliers',
				'HM' => 'Inhomogenity',
				'MP' => 'Missing Pattern'
			);

		return $nameMap[$name];

		/*
		switch($name){
			case 'OR':
				return 'Out-of-Range';
			case 'MG':
				return 'Missing Gap';
			case 'FV':
				return
			default:
		} */
	}

	static function yesterdayReport() {

		dd(self::getStartDate('Y-m-d 07:01', -1));

		$result_rain = DB::table('problems')
			->select(array('station_code', 'problem_type'))
			->where('data_type', '=', 'RAIN')
			// ->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			// ->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->where('end_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<', self::getStartDate('Y-m-d 07:00'))
			->get();

		$result_water = DB::table('problems')
			->select(array('station_code', 'problem_type'))
			->where('data_type', '=', 'WATER')
			// ->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			// ->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->where('end_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<', self::getStartDate('Y-m-d 07:00'))
			->get();

		$report_water = array(
				'OR' => array(
						'name' => getProblemName('OR'),
						'stations' => array()
					),
				'MG' => array(
						'name' => getProblemName('MG'),
						'stations' => array()
					),
				'FV' => array(
						'name' => getProblemName('FV'),
						'stations' => array()
					),
			);

		$report_rain = array(
				'OR' => array(
						'name' => getProblemName('OR'),
						'stations' => array()
					),
				'MG' => array(
						'name' => getProblemName('MG'),
						'stations' => array()
					),
				'FV' => array(
						'name' => getProblemName('FV'),
						'stations' => array()
					),
			);

		foreach ($result_rain as $r) {
			if(isset($report_rain[$r->problem_type])) {
				$report_rain[$r->problem_type]['stations'] []= $r->station_code;
			}
		}

		foreach ($result_water as $w) {
			if(isset($report_water[$w->problem_type])) {
				$report_water[$w->problem_type]['stations'] []= $w->station_code;
			}
		}

		return array("rain" => $report_rain, "water" => $report_water);

	}

	static function yesterdayStat(){
		// $results['RAIN'] = DB::table('problems')
		// 	->select(DB::raw('problem_type, count(*)'))
		// 	->where('data_type', '=', 'RAIN')
		// 	->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
		// 	->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
		// 	->groupBy('problem_type')
		// 	->get();
		// $results['WATER'] = DB::table('problems')
		// 	->select(DB::raw('problem_type, count(*)'))
		// 	->where('data_type', '=', 'WATER')
		// 	->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
		// 	->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
		// 	->groupBy('problem_type')
		// 	->get();
		// 	
		
		dd(self::getStartDate('Y-m-d 07:01', -1)."     ".self::getStartDate('Y-m-d 07:00'));

		$results['RAIN'] = DB::table('problems')
			->select(DB::raw('problem_type, count(*)'))
			->where('data_type', '=', 'RAIN')
			// ->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			// ->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->where('end_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<', self::getStartDate('Y-m-d 07:00'))
			->groupBy('problem_type')
			->get();
		$results['WATER'] = DB::table('problems')
			->select(DB::raw('problem_type, count(*)'))
			->where('data_type', '=', 'WATER')
			// ->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			// ->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->where('end_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<', self::getStartDate('Y-m-d 07:00'))
			->groupBy('problem_type')
			->get();

		$output = array(
			'RAIN' => array(
				'OR' => 0,
				'MG' => 0,
				'FV' => 0,
				'OL' => 0,
				'HM' => 0,
				'MP' => 0
			),
			'WATER' => array(
				'OR' => 0,
				'MG' => 0,
				'FV' => 0,
				'OL' => 0,
				'HM' => 0,
				'MP' => 0
			)
		);
		foreach($results as $type => $r)
			foreach($r as $result)
				$output[$type][$result->problem_type] = $result->count;
		return $output;
	}

	private static function getStartDate($format, $offset = 0) {
		if(intval(date('G')) < 7 || (intval(date('G')) == 7) && intval(date('i')) == 0) $offset -= 1;
		return date($format, time()+($offset*24*60*60));
	}

	function station(){
		return $this->hasOne('TeleStation', 'code', 'station_code');
	}

	private static function buildFullStationName($data){
		return $data['name'].' ต.'.$data['tambon_name'].' อ.'.$data['amphoe_name'].' จ.'.$data['province_name'];
	}

	function fullStationName(){
		$station = $this->station;
		return self::buildFullStationName((array) $station);
	}

	private static function buildEndTime($end_datetime){
		return date('H:i', strtotime($end_datetime));
	}

	function getEndTime(){
		return self::buildEndTime($this->end_datetime);
	}
}